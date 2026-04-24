<?php

return [
    'coverage' => [
        'enabled' => env('TEST_COVERAGE_ENABLED', false),
        'threshold' => env('TEST_COVERAGE_THRESHOLD', 80),
        'path' => storage_path('logs/coverage'),
    ],
    'suites' => [
        'unit' => ['path' => 'tests/Unit', 'timeout' => 30],
        'feature' => ['path' => 'tests/Feature', 'timeout' => 60],
        'integration' => ['path' => 'tests/Integration', 'timeout' => 120],
    ],
    'parallel' => env('TEST_PARALLEL', false),
    'stop_on_failure' => env('TEST_STOP_ON_FAILURE', false),
];