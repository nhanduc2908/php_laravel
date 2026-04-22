<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Menu items will be handled by config/menu.php
        // This seeder is for reference only
        DB::table('system_settings')->insert([
            [
                'key' => 'menu_structure',
                'value' => json_encode([
                    'super_admin' => ['dashboard', 'users', 'roles', 'servers', 'criteria', 'assessments', 'files', 'reports', 'audit', 'settings'],
                    'admin' => ['dashboard', 'users', 'servers', 'criteria', 'assessments', 'files', 'reports'],
                    'security_officer' => ['dashboard', 'servers', 'assessments', 'files', 'reports'],
                    'viewer' => ['dashboard', 'reports'],
                    'auditor' => ['dashboard', 'audit', 'reports'],
                ]),
                'group' => 'navigation',
                'type' => 'json',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}