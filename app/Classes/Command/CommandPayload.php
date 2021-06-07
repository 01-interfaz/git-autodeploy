<?php


namespace App\Classes\Command;


class CommandPayload
{
    public string $FolderLocation;

    private array $_gitBranch;
    private ?array $_request;

    public function SetRequest(array $request): void
    {
        $this->_request = $request;
    }

    public function GetRequest(): array
    {
        return $this->_request ?? [];
    }

    public function GetGitBranchs(): array
    {
        return $this->_gitBranch;
    }

    public function AddGitBranch(string $branchName): void
    {
        $this->_gitBranch[] = $branchName;
    }
}