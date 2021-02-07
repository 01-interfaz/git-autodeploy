<?php
set_time_limit(120);
define('APP_ROOT', rtrim(__DIR__, '/public') . '/');
require_once APP_ROOT . 'vendor/autoload.php';
require_once APP_ROOT . 'settings.php';

\App\Classes\FileLogger::default()->writeSeparator();
\App\Classes\FileLogger::default()->writeSeparator(\App\Classes\FileLogger::default()->getDate() . " Start Execution ");

$driver = null;

if (isset($_REQUEST['driver'])) {
    $driver_name = $_REQUEST['driver'];
    switch ($driver_name)
    {
        case "web": $driver =  new \App\Classes\Repository\WebDriver(); break;
        case "github": $driver =  new \App\Classes\Repository\GithubDriver(); break;
    }
}

if ($driver !== null)
{
    $deployer = new \App\Classes\Deployer($driver, ["folder" => "", "gitRepository" => ""]);
    $deployer->addCommand(new \App\Classes\Command\GitPullCommand());
    $deployer->run();
}
else
{
    \App\Classes\FileLogger::default()->writeError("Not select driver");
}

\App\Classes\FileLogger::default()->writeSeparator(\App\Classes\FileLogger::default()->getDate() . " End Execution ");
\App\Classes\FileLogger::default()->writeLine("");
\App\Classes\FileLogger::default()->close();

/*
$logs = App\Classes\FileLogger::default()->getLogStack();
foreach ($logs as $log) echo "  ".$log;
*/