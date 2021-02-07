<?php

namespace App\Classes;

use App\Classes\Command\ICommand;
use App\Classes\Repository\IDriver;
use mysql_xdevapi\Exception;

class Deployer
{
    private $commands = [];
    private $payload;
    private IDriver $driver;

    public function __construct(IDriver $driver, $payload)
    {
        $this->payload = $payload;
        $this->driver = $driver;
    }

    public function run()
    {
        header("Content-Type: text/plain");
        if ($this->runDriver($this->driver) && $this->runCommands())
        {
            FileLogger::default()->writeLine("Success!");
        }
    }

    private function runDriver(IDriver $driver) : bool
    {
        try {
            FileLogger::default()->writeLine("Read Content");
            if (!$driver->readContent()) throw new \Exception("Isn't possible read the content");
            FileLogger::default()->writeLine("Check Sender");
            if (!$driver->checkSender()) throw new \Exception("Isn't valid sender to this driver");
            FileLogger::default()->writeLine("Check Token");
            if (!$driver->checkToken()) throw new \Exception("Isn't valid token");
            return true;
        }
        catch (\Exception $e){
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    private function runCommands() : bool
    {
        CommandSSH::default()->openSession();
        FileLogger::default()->writeLine("Attempt run " . count($this->commands) . " commands");
        $result = true;
        foreach ($this->commands as $command)
        {
            FileLogger::default()->writeLine("Begin run command " . get_class($command));
            if(!$command->prepare($this->payload)) { $result = false; break;}
            if(!$command->execute()) { $result = false; break;}
            FileLogger::default()->writeLine("End run command " . get_class($command));
        }
        CommandSSH::default()->closeSession();
        return $result;
    }

    public function addCommand(ICommand $command)
    {
        $this->commands[] = $command;
    }

    private function readSHA()
    {
        $sha = false;

        if (isset($this->json["checkout_sha"])) {
            $sha = $this->json["checkout_sha"];
        } elseif (isset($_SERVER["checkout_sha"])) {
            $sha = $_SERVER["checkout_sha"];
        } elseif (isset($_GET["sha"])) {
            $sha = $_GET["sha"];
        }

        return $sha;
    }
}