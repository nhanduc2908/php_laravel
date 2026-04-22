<?php

namespace App\Wrappers;

class EnvironmentWrapper
{
    public function get($key, $default = null)
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        return $this;
    }

    public function has($key)
    {
        return isset($_ENV[$key]) || isset($_SERVER[$key]);
    }
}