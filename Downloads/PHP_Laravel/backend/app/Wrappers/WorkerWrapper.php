<?php

namespace App\Wrappers;

class WorkerWrapper
{
    protected $queue;

    public function __construct(QueueWrapper $queue)
    {
        $this->queue = $queue;
    }

    public function work($queue = null)
    {
        while (true) {
            $job = $this->queue->pop($queue);
            if ($job) {
                try {
                    $job->handle();
                } catch (\Exception $e) {
                    $job->failed($e);
                }
            } else {
                sleep(1);
            }
        }
    }
}