<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentFileSeeder extends Seeder
{
    public function run()
    {
        DB::table('assessment_files')->insert([
            [
                'title' => 'Security Assessment Report Q1 2024',
                'content' => 'This is the full security assessment report for Q1 2024...',
                'server_id' => 1,
                'created_by' => 3,
                'status' => 'published',
                'version' => 1,
                'file_path' => null,
                'file_type' => 'document',
                'file_size' => null,
                'tags' => json_encode(['report', 'q1-2024', 'security']),
                'due_date' => '2024-03-31',
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Vulnerability Scan Results - Production',
                'content' => 'Detailed vulnerability scan results for production servers...',
                'server_id' => 1,
                'created_by' => 3,
                'status' => 'published',
                'version' => 2,
                'file_path' => null,
                'file_type' => 'spreadsheet',
                'file_size' => null,
                'tags' => json_encode(['vulnerability', 'scan', 'production']),
                'due_date' => '2024-02-28',
                'priority' => 'critical',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Compliance Checklist Draft',
                'content' => 'Draft compliance checklist for ISO 27001...',
                'server_id' => 2,
                'created_by' => 3,
                'status' => 'draft',
                'version' => 1,
                'file_path' => null,
                'file_type' => 'document',
                'file_size' => null,
                'tags' => json_encode(['compliance', 'iso27001', 'draft']),
                'due_date' => '2024-04-15',
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}