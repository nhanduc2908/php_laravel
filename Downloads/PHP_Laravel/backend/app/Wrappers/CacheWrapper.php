<?php

namespace App\Wrappers;

class CacheWrapper
{
    protected $driver;

    public function __construct($driver = null)
    {
        $this->driver = $driver ?: new FileCacheWrapper();
    }

    public function get($key, $default = null)
    {
        return $this->driver->get($key, $default);
    }

    public function set($key, $value, $ttl = 3600)
    {
        return $this->driver->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->driver->delete($key);
    }

    public function remember($key, $ttl, $callback)
    {
        $value = $this->get($key);
        if ($value !== null) return $value;
        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }
}