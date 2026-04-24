<?php

namespace App\Wrappers;

class EventWrapper
{
    protected $listeners = [];

    public function listen($event, $listener)
    {
        $this->listeners[$event][] = $listener;
    }

    public function dispatch($event, $payload = [])
    {
        $eventName = is_string($event) ? $event : get_class($event);
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                if (is_string($listener)) {
                    (new $listener())->handle($event);
                } else {
                    $listener($event);
                }
            }
        }
    }
}