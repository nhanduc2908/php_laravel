<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup commands
Artisan::command('backup:run', function () {
    $this->info('Starting backup...');
    $this->call('backup:run', ['--only-db' => true]);
    $this->info('Backup completed!');
})->purpose('Run database backup');

// Assessment commands
Artisan::command('assessment:run {server_id}', function ($serverId) {
    $this->info("Running assessment for server ID: {$serverId}");
    $this->call('assessment:run', ['server_id' => $serverId]);
    $this->info('Assessment completed!');
})->purpose('Run security assessment for a server');

// Cleanup commands
Artisan::command('logs:clean', function () {
    $this->info('Cleaning old logs...');
    $this->call('logs:clean', ['--days' => 30]);
    $this->info('Logs cleaned!');
})->purpose('Clean old log files')->daily();

// Test commands
Artisan::command('test:all', function () {
    $this->info('Running all tests...');
    $this->call('test', ['--testsuite' => 'all']);
    $this->info('Tests completed!');
})->purpose('Run all test suites');

// User management
Artisan::command('user:create-admin {email} {name}', function ($email, $name) {
    $password = $this->secret('Enter password');
    $this->call('user:create', [
        'email' => $email,
        'name' => $name,
        'password' => $password,
        '--role' => 'admin'
    ]);
    $this->info("Admin user {$email} created!");
})->purpose('Create a new admin user');

// System maintenance
Artisan::command('maintenance:enable', function () {
    $this->call('down', ['--retry' => 60, '--secret' => 'maintenance-mode']);
    $this->info('Maintenance mode enabled');
})->purpose('Enable maintenance mode');

Artisan::command('maintenance:disable', function () {
    $this->call('up');
    $this->info('Maintenance mode disabled');
})->purpose('Disable maintenance mode');

// Cache management
Artisan::command('cache:clear-all', function () {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->info('All cache cleared!');
})->purpose('Clear all application cache');