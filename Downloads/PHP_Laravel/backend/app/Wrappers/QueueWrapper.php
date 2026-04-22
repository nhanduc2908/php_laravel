<?php

namespace App\Wrappers;

class QueueWrapper
{
    protected $driver;

    public function __construct($driver = null)
    {
        $this->driver = $driver ?: new DatabaseQueueWrapper();
    }

    public function push($job, $data = [])
    {
        return $this->driver->push($job, $data);
    }

    public function later($delay, $job, $data = [])
    {
        return $this->driver->later($delay, $job, $data);
    }

    public function pop($queue = null)
    {
        return $this->driver->pop($queue);
    }
}