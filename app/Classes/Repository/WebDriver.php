<?php

namespace App\Classes\Repository;


class WebDriver implements IDriver
{
    public function readContent(): ?array
    {
        return request();
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