<?php

namespace App\Classes;

class FileLogger
{
    private $file;
    private int $time;
    private array $logStack = [];

    private static ?FileLogger $instance = null;

    public static function default(): FileLogger
    {
        if (self::$instance === null) self::$instance = new static('/default.log');
        return self::$instance;
    }

    public function __construct($filePath)
    {
        try {
            $this->file = fopen(APP_ROOT . $filePath, "a");
        } catch (Exception | Throwable $e) {
            $this->writeError("Not permission for write LOG file on $filePath");
        }
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
        $this->writeError("[$sender] " . $content);
    }

    public function writeLine($content)
    {
        $this->write($content . "\n");
    }

    /** @noinspection PhpUnused */
    public function writeLineFrom($sender, $content)
    {
        $sender = get_class($sender);
        $this->writeLine("[$sender] " . $content);
    }

    /** @noinspection PhpUnused */
    public function getLogStack(): array
    {
        return $this->logStack;
    }

    public function write($content)
    {
        $this->logStack[] = $content;
        echo $content;
        if ($this->file != null) fputs($this->file, $content);
    }

    public function close()
    {
        if ($this->file != null) fclose($this->file);
    }
}