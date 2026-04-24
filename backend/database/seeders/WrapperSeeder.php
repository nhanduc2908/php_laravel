<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WrapperSeeder extends Seeder
{
    public function run()
    {
        DB::table('system_settings')->insert([
            [
                'key' => 'wrapper_config',
                'value' => json_encode([
                    'enabled' => true,
                    'cache_driver' => 'redis',
                    'cache_ttl' => 3600,
                    'log_channel' => 'daily',
                    'api_version' => 'v1',
                    'rate_limit' => 60,
                ]),
                'group' => 'wrapper',
                'type' => 'json',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'api_wrapper_format',
                'value' => 'standard',
                'group' => 'wrapper',
                'type' => 'string',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}