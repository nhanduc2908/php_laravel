<?php

namespace App\Wrappers;

class FileLogWrapper
{
    protected $path;

    public function __construct($path = null)
    {
        $this->path = $path ?: storage_path('logs/app.log');
        $dir = dirname($this->path);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }

    public function write($level, $message, $context = [])
    {
        $date = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $log = "[{$date}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        file_put_contents($this->path, $log, FILE_APPEND | LOCK_EX);
    }
}
