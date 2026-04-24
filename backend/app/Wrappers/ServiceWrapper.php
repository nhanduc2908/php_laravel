<?php

namespace App\Wrappers;

class ServiceWrapper
{
    protected $bindings = [];
    protected $instances = [];

    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract]['concrete'] ?? $abstract;
        $object = is_string($concrete) ? new $concrete : $concrete;
        
        if ($this->bindings[$abstract]['shared'] ?? false) {
            $this->instances[$abstract] = $object;
        }
        return $object;
    }
}