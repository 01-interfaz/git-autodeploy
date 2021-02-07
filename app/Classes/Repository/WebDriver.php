<?php

namespace App\Classes\Repository;

use App\Classes\FileLogger;

class WebDriver implements IDriver
{
    private $token;
    private $content;

    public function readContent(): bool
    {
        try {
            $content = file_get_contents("php://input");
            return true;
        } catch (\Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function checkToken(): bool
    {
        try {
            if (!isset($_REQUEST["token"])) throw new \Exception("Has't token");
            $this->token = $_REQUEST['token'];
            return TOKEN === $this->token;
        }
        catch (\Exception $e)
        {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function checkSender(): bool
    {
        return true;
    }
}