<?php
// config/logging.php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return [
    'default' => env('LOG_CHANNEL', 'stack'),
    
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        
        // ==================== MAIN LOGS ====================
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],
        
        // ==================== AUTH LOGS ====================
        'auth' => [
            'driver' => 'daily',
            'path' => storage_path('logs/auth.log'),
            'level' => 'info',
            'days' => 30,
        ],
        
        // ==================== SCAN LOGS ====================
        'scan' => [
            'driver' => 'daily',
            'path' => storage_path('logs/scan.log'),
            'level' => 'info',
            'days' => 60,
        ],
        
        // ==================== ERROR LOGS ====================
        'error' => [
            'driver' => 'daily',
            'path' => storage_path('logs/error.log'),
            'level' => 'error',
            'days' => 90,
        ],
        
        // ==================== ATTACK LOGS ====================
        'attack' => [
            'driver' => 'daily',
            'path' => storage_path('logs/attack.log'),
            'level' => 'warning',
            'days' => 180,
        ],
        
        // ==================== API LOGS ====================
        'api' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api.log'),
            'level' => 'info',
            'days' => 30,
        ],
        
        // ==================== DATABASE LOGS ====================
        'database' => [
            'driver' => 'daily',
            'path' => storage_path('logs/database.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        
        // ==================== CRON LOGS ====================
        'cron' => [
            'driver' => 'daily',
            'path' => storage_path('logs/cron.log'),
            'level' => 'info',
            'days' => 30,
        ],
        
        // ==================== TEST LOGS ====================
        'test' => [
            'driver' => 'daily',
            'path' => storage_path('logs/test.log'),
            'level' => 'debug',
            'days' => 7,
        ],
        
        // ==================== WRAPPER LOGS ====================
        'wrapper' => [
            'driver' => 'daily',
            'path' => storage_path('logs/wrapper.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        
        // ==================== LANGUAGE LOGS ====================
        'language' => [
            'driver' => 'daily',
            'path' => storage_path('logs/language.log'),
            'level' => 'info',
            'days' => 14,
        ],
    ],
];