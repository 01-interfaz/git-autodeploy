<?php

namespace App\Classes;

use App\Classes\Command\CommandPayload;
use App\Classes\Command\ICommand;
use App\Classes\Repository\IDriver;
use Exception;

class Deployer
{
    private array $commands = [];
    private CommandPayload $payload;
    private IDriver $driver;
    private ?array $driver_content;

    public function __construct(IDriver $driver, CommandPayload $payload)
    {
        $this->payload = $payload;
        $this->driver = $driver;
    }

    public function run()
    {
        header("Content-Type: text/plain");
        if ($this->runDriver($this->driver) && $this->runCommands()) {
            FileLogger::default()->writeLine("Success!");
        }
    }

    private function runDriver(IDriver $driver): bool
    {
        try {
            FileLogger::default()->writeLine("Starting Read Content");
            $this->driver_content = $driver->readContent();
            if ($this->driver_content == null) throw new Exception("Isn't possible read the content");
            FileLogger::default()->writeLine("Check Sender");
            if (!$driver->checkSender()) throw new Exception("Isn't valid sender to this driver");
            FileLogger::default()->writeLine("Check Token");
            if (!$driver->checkToken()) throw new Exception("Isn't valid token");
            return true;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    private function runCommands(): bool
    {
        FileLogger::default()->writeLine("Attempt run " . count($this->commands) . " commands");
        $result = true;
        $this->payload->SetRequest($this->driver_content);

        //PREPARING
        foreach ($this->commands as $command) {
            FileLogger::default()->writeLine("Begin preparing command " . get_class($command));
            if (!$command->prepare($this->payload)) {
                $result = false;
                break;
            }
            FileLogger::default()->writeLine("End preparing command " . get_class($command));
        }

        //EXECUTE
        if ($result) {
            CommandSSH::default()->openSession();
            foreach ($this->commands as $command) {
                FileLogger::default()->writeLine("Begin run command " . get_class($command));
                if (!$command->execute()) {
                    $result = false;
                    break;
                }
                FileLogger::default()->writeLine("End run command " . get_class($command));
            }
            CommandSSH::default()->closeSession();
        }
        return $result;
    }

    public function addCommand(ICommand $command)
    {
        $this->commands[] = $command;
    }
}