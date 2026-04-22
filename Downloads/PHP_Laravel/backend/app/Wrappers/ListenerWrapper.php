ListenerWrapper.php<?php

namespace App\Wrappers;

abstract class ListenerWrapper
{
    abstract public function handle($event);
}