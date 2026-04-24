<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Set testing environment
        $app->loadEnvironmentFrom('.env.testing');
        
        // Configure database for testing (in-memory SQLite)
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ],
            'cache.default' => 'array',
            'queue.default' => 'sync',
            'mail.default' => 'array',
            'broadcasting.default' => 'log',
            'session.driver' => 'array',
        ]);

        return $app;
    }

    /**
     * Prepare for tests
     */
    protected function prepareForTests()
    {
        // Disable debug mode for tests
        config(['app.debug' => false]);
        
        // Set test token
        config(['jwt.ttl' => 3600]);
        
        // Disable rate limiting for tests
        config(['rate_limiter.enabled' => false]);
    }

    /**
     * Reset the application for tests
     */
    protected function resetApplication()
    {
        $this->refreshApplication();
        $this->prepareForTests();
    }
}