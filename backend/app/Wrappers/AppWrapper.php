<?php

namespace App\Wrappers;

class AppWrapper
{
    protected static $instance;
    protected $booted = false;
    protected $services = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(array $config = [])
    {
        $this->registerBaseServices();
        $this->loadEnvironment();
        return $this;
    }

    public function boot()
    {
        if ($this->booted) return;
        foreach ($this->services as $service) {
            if (method_exists($service, 'boot')) $service->boot();
        }
        $this->booted = true;
    }

    protected function registerBaseServices()
    {
        $this->services['config'] = app(ConfigWrapper::class);
        $this->services['env'] = app(EnvironmentWrapper::class);
    }

    protected function loadEnvironment()
    {
        if (file_exists(base_path('.env'))) {
            $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
            $dotenv->load();
        }
    }
}