<?php

return [
    'enabled' => env('WRAPPER_ENABLED', true),
    'cache' => [
        'driver' => env('WRAPPER_CACHE_DRIVER', 'file'),
        'ttl' => env('WRAPPER_CACHE_TTL', 3600),
    ],
    'log' => [
        'channel' => env('WRAPPER_LOG_CHANNEL', 'daily'),
        'level' => env('WRAPPER_LOG_LEVEL', 'info'),
    ],
    'api' => [
        'default_version' => env('API_DEFAULT_VERSION', 'v1'),
        'rate_limit' => env('API_RATE_LIMIT', 60),
    ],
];