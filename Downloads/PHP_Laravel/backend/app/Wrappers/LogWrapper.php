<?php

namespace App\Wrappers;

class LogWrapper
{
    protected $driver;

    public function __construct($driver = null)
    {
        $this->driver = $driver ?: new FileLogWrapper();
    }

    public function emergency($message, $context = []) { $this->log('emergency', $message, $context); }
    public function alert($message, $context = []) { $this->log('alert', $message, $context); }
    public function critical($message, $context = []) { $this->log('critical', $message, $context); }
    public function error($message, $context = []) { $this->log('error', $message, $context); }
    public function warning($message, $context = []) { $this->log('warning', $message, $context); }
    public function notice($message, $context = []) { $this->log('notice', $message, $context); }
    public function info($message, $context = []) { $this->log('info', $message, $context); }
    public function debug($message, $context = []) { $this->log('debug', $message, $context); }

    protected function log($level, $message, $context)
    {
        $this->driver->write($level, $message, $context);
    }
}