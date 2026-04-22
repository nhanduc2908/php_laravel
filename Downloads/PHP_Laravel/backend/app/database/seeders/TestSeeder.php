<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    public function run()
    {
        // Test suites
        DB::table('test_suites')->insert([
            [
                'name' => 'Unit Tests',
                'description' => 'Unit tests for individual components',
                'tests_count' => 0,
                'passed' => 0,
                'failed' => 0,
                'last_run_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Feature Tests',
                'description' => 'Feature tests for API endpoints',
                'tests_count' => 0,
                'passed' => 0,
                'failed' => 0,
                'last_run_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Integration Tests',
                'description' => 'Integration tests for services',
                'tests_count' => 0,
                'passed' => 0,
                'failed' => 0,
                'last_run_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        // Sample test results
        DB::table('test_results')->insert([
            [
                'test_suite' => 'Unit Tests',
                'test_name' => 'AuthTest::test_login_success',
                'status' => 'passed',
                'duration' => 125.5,
                'message' => 'Test passed successfully',
                'trace' => null,
                'coverage' => 85.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'test_suite' => 'Unit Tests',
                'test_name' => 'AuthTest::test_login_failed',
                'status' => 'passed',
                'duration' => 98.3,
                'message' => 'Test passed successfully',
                'trace' => null,
                'coverage' => 85.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'test_suite' => 'Feature Tests',
                'test_name' => 'ApiAuthTest::test_api_login',
                'status' => 'passed',
                'duration' => 156.7,
                'message' => 'Test passed successfully',
                'trace' => null,
                'coverage' => 78.2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}