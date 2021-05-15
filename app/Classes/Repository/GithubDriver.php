<?php


namespace App\Classes\Repository;


use App\Classes\FileLogger;

class GithubDriver implements IDriver
{
    private string $token;
    private string $algo;

    public function readContent(): ?array
    {
        $value = null;
        try {
            $content = json_decode(file_get_contents("php://input"), true);
            dd($content);
            if (key_exists("ref", $content)) {
                $branch = explode("/", $content["ref"]);
                $value[] = ["branch" => end($branch)];
            }
        } catch (\Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
        }
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