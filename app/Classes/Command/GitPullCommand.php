<?php

namespace App\Classes\Command;

use app\Classes\CommandSSH;
use App\Classes\FileLogger;
use Exception;

class GitPullCommand implements ICommand
{
    private string $folder;
    private array $gitBranch;

    public function prepare(CommandPayload $payload): bool
    {
        try {
            if (!isset($payload->FolderLocation)) throw new Exception("Has't folder location");
            $this->folder = $payload->FolderLocation;

            if (!isset($payload->GitBranch)) throw new Exception("Has't hit repository");
            $this->gitBranch = $payload->GetGitBranchs();

            return true;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function execute(): bool
    {
        try {
            $sshResult = CommandSSH::default()->run(["cd $this->folder", 'sh git_update.sh']);
            FileLogger::default()->writeLine($sshResult);
            return true;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }
}