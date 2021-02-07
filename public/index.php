<?php

define('APP_ROOT', rtrim(__DIR__, '/public') . '/');
require_once APP_ROOT . 'vendor/autoload.php';
require_once APP_ROOT . 'settings.php';

\App\Classes\FileLogger::default()->writeSeparator();
\App\Classes\FileLogger::default()->writeSeparator(\App\Classes\FileLogger::default()->getDate() . " Start Execution ");

$deployer = new \App\Classes\Deployer(new \App\Classes\Repository\WebDriver(), ["folder" => "", "gitRepository" => ""]);
$deployer->addCommand(new \App\Classes\Command\GitPullCommand());
$deployer->run();

\App\Classes\FileLogger::default()->writeSeparator(\App\Classes\FileLogger::default()->getDate() . " End Execution ");
\App\Classes\FileLogger::default()->writeLine("");
\App\Classes\FileLogger::default()->close();

$logs = App\Classes\FileLogger::default()->getLogStack();
foreach ($logs as $log) echo "  ".$log;