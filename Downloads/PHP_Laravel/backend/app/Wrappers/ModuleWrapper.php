<?php

namespace App\Wrappers;

class ModuleWrapper
{
    protected $modules = [];

    public function register($name, $config)
    {
        $this->modules[$name] = $config;
    }

    public function isEnabled($name)
    {
        return $this->modules[$name]['enabled'] ?? false;
    }

    public function enable($name)
    {
        if (isset($this->modules[$name])) {
            $this->modules[$name]['enabled'] = true;
        }
    }

    public function disable($name)
    {
        if (isset($this->modules[$name])) {
            $this->modules[$name]['enabled'] = false;
        }
    }
}