<?php

namespace App\Classes\Command;

use app\Classes\CommandSSH;
use App\Classes\FileLogger;

class GitPullCommand implements ICommand
{
    private $folder;
    private $gitRepository;

    public function prepare($payload): bool
    {
        try {
            if (!isset($payload['folder'])) throw new \Exception("Has't folder location");
            $this->folder = $payload['folder'];

            if (!isset($payload['gitRepository'])) throw new \Exception("Has't hit repository");
            $this->gitRepository = $payload['gitRepository'];

            return true;
        }
        catch (\Exception $e)
        {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function execute(): bool
    {
        try{
            $sshResult = CommandSSH::default()->run([
                'cd '.$this->folder,
                'sh git_update.sh'
            ]);
            FileLogger::default()->writeLine($sshResult);
            return true;
        }
        catch (\Exception $e)
        {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }
}