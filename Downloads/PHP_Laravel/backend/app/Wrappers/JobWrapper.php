<?php

namespace App\Wrappers;

class JobWrapper
{
    protected $job;
    protected $data;

    public function __construct($job, $data = [])
    {
        $this->job = $job;
        $this->data = $data;
    }

    public function handle()
    {
        if (class_exists($this->job)) {
            $instance = new $this->job();
            if (method_exists($instance, 'handle')) {
                return $instance->handle($this->data);
            }
        }
        throw new \Exception("Job class {$this->job} not found or missing handle method");
    }

    public function failed($exception)
    {
        // Log failed job
    }
}