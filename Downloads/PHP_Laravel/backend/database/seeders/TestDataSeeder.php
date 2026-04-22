<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Sample assessment results for testing
        DB::table('assessment_results')->insert([
            [
                'server_id' => 1,
                'criteria_id' => 1,
                'score' => 85.5,
                'evidence' => 'Screenshot of policy document',
                'note' => 'Policy is well documented but needs annual review',
                'assessed_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'server_id' => 1,
                'criteria_id' => 2,
                'score' => 92.0,
                'evidence' => 'Organization chart showing roles',
                'note' => 'Clear role assignment for security',
                'assessed_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        // Sample audit logs
        DB::table('audit_logs')->insert([
            [
                'user_id' => 1,
                'action' => 'login',
                'resource' => 'user',
                'resource_id' => '1',
                'old_values' => null,
                'new_values' => json_encode(['ip' => '192.168.1.100']),
                'ip' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'action' => 'create',
                'resource' => 'server',
                'resource_id' => '1',
                'old_values' => null,
                'new_values' => json_encode(['name' => 'Production Server']),
                'ip' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}