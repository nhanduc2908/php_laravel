<?php

namespace App\Wrappers;

abstract class SubscriberWrapper
{
    abstract public function subscribe($events);
}