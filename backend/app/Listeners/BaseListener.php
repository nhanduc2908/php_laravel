<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

abstract class BaseListener implements ShouldQueue
{
    public $queue = 'listeners';
    public $delay = 0;
    public $tries = 3;
    
    public function failed($event, $exception)
    {
        \Illuminate\Support\Facades\Log::error('Listener failed', [
            'listener' => static::class,
            'event' => get_class($event),
            'error' => $exception->getMessage()
        ]);
    }
}