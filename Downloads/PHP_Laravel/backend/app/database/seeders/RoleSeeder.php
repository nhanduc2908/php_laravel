<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Quản trị viên cao cấp, toàn quyền hệ thống',
                'level' => 100,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Quản trị viên hệ thống',
                'level' => 80,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Security Officer',
                'slug' => 'security_officer',
                'description' => 'Cán bộ an ninh, thực hiện đánh giá',
                'level' => 60,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Người xem, chỉ xem báo cáo',
                'level' => 40,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Auditor',
                'slug' => 'auditor',
                'description' => 'Kiểm toán viên, xem audit logs',
                'level' => 50,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}