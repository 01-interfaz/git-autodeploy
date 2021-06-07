<?php

namespace App\Classes\Repository;

use App\Classes\FileLogger;
use Exception;

class WebDriver implements IDriver
{
    private string $token;

    public function readContent(): ?array
    {
        try {
            return request();
        } catch (Exception $e) {
            FileLogger::default()->writeErrorFrom($this, $e->getMessage());
            return null;
        }
    }

    public function checkToken(): bool
    {
        return true;
    }

    public function checkSender(): bool
    {
        return true;
    }
}