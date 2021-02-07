<?php

namespace App\Classes;

class FileLogger
{
    private $file;
    private $time;
    private $logStack = [];

    private static ?FileLogger $instance = null;

    public static function default() : FileLogger
    {
        if (self::$instance === null) self::$instance = new static('/default.log');
        return self::$instance;
    }

    public function __construct($filePath)
    {
        $this->file = fopen(APP_ROOT . $filePath, "a");
        $this->time = time();

        date_default_timezone_set("UTC");
    }

    public function getDate()
    {
        return date("d-m-Y (H:i:s)", $this->time);
    }

    public function writeSeparator($content = "")
    {
        $text = str_pad($content, 40, "~*", STR_PAD_BOTH);
        $this->writeLine("$text");
    }

    public function writeError($content)
    {
        $this->writeLine("[ERROR] $content");
    }

    public function writeErrorFrom($sender, $content)
    {
        $sender = get_class($sender);
        $this->writeError( "[$sender] " . $content);
    }

    public function writeLine($content)
    {
        $this->write($content."\n");
    }

    public function writeLineFrom($sender, $content)
    {
        $sender = get_class($sender);
        $this->writeLine( "[$sender] " . $content);
    }

    public function getLogStack()
    {
        return $this->logStack;
    }

    public function write($content)
    {
        $this->logStack[] = $content;
        fputs($this->file, $content);
    }

    public function close()
    {
        fclose($this->file);
    }
}