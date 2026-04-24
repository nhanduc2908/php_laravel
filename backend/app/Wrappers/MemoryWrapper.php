<?php

namespace App\Wrappers;

class MemoryWrapper
{
    protected $storage = [];

    public function get($key, $default = null)
    {
        if (!isset($this->storage[$key])) return $default;
        $item = $this->storage[$key];
        if ($item['expires'] < time()) {
            unset($this->storage[$key]);
            return $default;
        }
        return $item['value'];
    }

    public function set($key, $value, $ttl = 3600)
    {
        $this->storage[$key] = ['value' => $value, 'expires' => time() + $ttl];
        return true;
    }

    public function delete($key)
    {
        unset($this->storage[$key]);
        return true;
    }

    public function flush()
    {
        $this->storage = [];
        return true;
    }
}