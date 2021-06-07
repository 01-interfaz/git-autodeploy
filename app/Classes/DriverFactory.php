<?php


namespace App\Classes;


use App\Classes\Repository\GithubDriver;
use App\Classes\Repository\IDriver;
use App\Classes\Repository\WebDriver;

class DriverFactory
{
    public static function create(string $driver_name): IDriver
    {
        switch ($driver_name) {
            case "web":
                return new WebDriver();
            case "github":
                return new GithubDriver();
        }
    }
}