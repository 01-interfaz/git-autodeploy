<?php

use App\Classes\Command\CommandPayload;
use App\Classes\Command\GitPullCommand;
use App\Classes\Deployer;
use App\Classes\FileLogger;
use App\Classes\Repository\GithubDriver;
use App\Classes\Repository\WebDriver;



set_time_limit(120);
define('APP_ROOT', rtrim(__DIR__, '/public') . '/');

/** @noinspection PhpIncludeInspection */
require_once APP_ROOT . 'app/Helpers/Default.php';
/** @noinspection PhpIncludeInspection */
require_once APP_ROOT . 'vendor/autoload.php';
/** @noinspection PhpIncludeInspection */
require_once APP_ROOT . 'settings.php';

FileLogger::default()->writeSeparator();
FileLogger::default()->writeSeparator(FileLogger::default()->getDate() . " Start Execution ");

$driver = null;

if (isset($_REQUEST['driver'])) {
    $driver_name = $_REQUEST['driver'];

    switch ($driver_name) {
        case "web":
            $driver = new WebDriver();
            break;
        case "github":
            $driver = new GithubDriver();
            break;
    }
}

if ($driver !== null) {
    $payload = new CommandPayload();
    $payload->FolderLocation = "";
    $payload->AddGitBranch("master");

    $deployer = new Deployer($driver, $payload);
    $deployer->addCommand(new GitPullCommand());
    $deployer->run();
} else {
    FileLogger::default()->writeError("Not select driver");
}

FileLogger::default()->writeSeparator(FileLogger::default()->getDate() . " End Execution ");
FileLogger::default()->writeLine("");
FileLogger::default()->close();