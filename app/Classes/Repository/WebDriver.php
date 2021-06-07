<?php

namespace App\Classes\Repository;


class WebDriver implements IDriver
{
    public function readContent(): ?array
    {
        $values = [];
        $values["branch"] = request_input('branch');
        return $values;
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