<?php

namespace App\Classes\Repository;


class WebDriver implements IDriver
{
    public function readContent(): ?array
    {
        request();
        dd($_SERVER);
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