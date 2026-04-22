<?php

namespace App\Wrappers;

class ConfigWrapper
{
    protected $items = [];

    public function load($path = null)
    {
        $path = $path ?? config_path();
        foreach (glob("{$path}/*.php") as $file) {
            $key = basename($file, '.php');
            $this->items[$key] = require $file;
        }
        return $this;
    }

    public function get($key, $default = null)
    {
        return data_get($this->items, $key, $default);
    }

    public function set($key, $value)
    {
        data_set($this->items, $key, $value);
        return $this;
    }

    public function has($key)
    {
        return data_get($this->items, $key) !== null;
    }
}