<?php

use App\Classes\Command\GitPullCommand;
use App\Classes\FileLogger;

ini_set("allow_url_fopen", true);
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

if (TOKEN == request_input('token')) {
    $webHook = new \App\Classes\WebHook(APP_ROOT . "webhook.json");
    $webHookError = "";
    if ($webHook->validate($webHookError)) {
        $deployer = $webHook->getDeployer();
        $deployer->addCommand(new GitPullCommand());
        $deployer->run();
    } else {
        FileLogger::default()->writeError($webHookError);
    }
} else {
    FileLogger::default()->writeError("invalid token");
}

FileLogger::default()->writeSeparator(FileLogger::default()->getDate() . " End Execution ");
FileLogger::default()->writeLine("");
FileLogger::default()->close();