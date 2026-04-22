<?php

return [
    'supported_locales' => ['en', 'vi', 'ja'],
    'fallback_locale' => 'en',
    'cache_translations' => env('CACHE_TRANSLATIONS', true),
    'paths' => [
        resource_path('lang'),
        base_path('lang'),
    ],
];