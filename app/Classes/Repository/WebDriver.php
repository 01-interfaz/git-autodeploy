<?php

namespace App\Classes\Repository;

use App\Classes\FileLogger;
use Exception;

class WebDriver implements IDriver
{
    private string $token;
    private string $content;

    public function readContent(): bool
    {
        try {
            $this->content = file_get_contents("php://input");
            file_put_contents("content.json", $this->content);
            return true;
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    public function checkToken(): bool
    {
        try {
            if (!isset($_REQUEST["token"])) throw new Exception("Has't token");
            $this->token = $_REQUEST['token'];
            return TOKEN === $this->token;
        }
        catch (Exception $e)
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