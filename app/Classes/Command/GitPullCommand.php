<?php

namespace App\Classes\Command;

use app\Classes\CommandSSH;
use App\Classes\FileLogger;
use Exception;

class GitPullCommand implements ICommand
{
    private string $folder;

    public function prepare(CommandPayload $payload): bool
    {
        try {
            if (!isset($payload->FolderLocation)) throw new Exception("Has't folder location");
            $this->folder = $payload->FolderLocation;
            $v = $this->checkBranch($payload);
            if (!$v) FileLogger::default()->writeLineFrom($this, "Exit invalid branch");
            return $v;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    private function checkBranch(CommandPayload $payload): bool
    {
        foreach ($payload->GetGitBranchs() as $branch) {
            if (self::compareStringNoSensitiveCase($payload->GetRequest()[0]["branch"], $branch)) return true;
        }
        return false;
    }

    private static function compareStringNoSensitiveCase($a, $b): bool
    {
        //FileLogger::default()->writeLine("$a = $b");
        return strcasecmp($a, $b) == 0;
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