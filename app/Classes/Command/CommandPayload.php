<?php


namespace App\Classes\Command;


class CommandPayload
{
    public string $FolderLocation;
    private array $_gitBranch;

    public function GetGitBranchs(): array
    {
        return $this->_gitBranch;
    }

    public function AddGitBranch(string $branchName): void
    {
        $this->_gitBranch[] = $branchName;
    }
}