<?php

use App\Classes\Command\CommandPayload;
use App\Classes\Command\GitPullCommand;
use App\Classes\Deployer;
use App\Classes\FileLogger;


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

$webHook = new \App\Classes\WebHook("../webhook.json");
$webHookError = "";
if ($webHook->validate($webHookError)) {
    $deployer = $webHook->getDeployer();
    $deployer->addCommand(new GitPullCommand());
    $deployer->run();
} else {
    FileLogger::default()->writeError($webHookError);
}

FileLogger::default()->writeSeparator(FileLogger::default()->getDate() . " End Execution ");
FileLogger::default()->writeLine("");
FileLogger::default()->close();