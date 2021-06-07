<?php


namespace App\Classes\Repository;


use App\Classes\FileLogger;

class GithubDriver implements IDriver
{
    public function readContent(): ?array
    {
        if (!request_header_validate("X-GitHub-Event", "push")) {
            FileLogger::default()->writeErrorFrom($this, "invalid event type, require 'push' event");
            return null;
        }

        if (!request_input_has('ref')) {
            FileLogger::default()->writeErrorFrom($this, "invalid content, require 'ref' value");
            return null;
        }

        $value = null;
        $branch = explode("/", request_input('ref'));
        $value[] = ["branch" => end($branch)];
        return $value;
    }

    public function checkSender(): bool
    {
        try {
            return true;
        } catch (\Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function checkToken(): bool
    {
        return true;
    }
}