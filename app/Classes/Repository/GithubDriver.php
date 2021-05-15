<?php


namespace App\Classes\Repository;


use App\Classes\FileLogger;

class GithubDriver implements IDriver
{
    private $token;
    private $content;
    private $algo;

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

    public function checkSender(): bool
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
            if (!isset($_SERVER["HTTP_X_HUB_SIGNATURE"]))throw  new \Exception('Is not github request');
            list($algo, $token) = explode("=", $_SERVER["HTTP_X_HUB_SIGNATURE"], 2) + array("", "");
            $this->token = $token;
            $this->algo = $algo;
            return $this->isTrust();
        } catch (\Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return false;
        }
    }

    private function isTrust() : bool
    {
        try {
            //if (!$this->token === hash_hmac($this->signature, $this->content, TOKEN))
            //    throw new \Exception('Request or token is not trust');
            return true;
        }
        catch (\Exception $e)
        {
            FileLogger::default()->writeErrorFrom($this,$e->getMessage());
            return false;
        }
    }
}