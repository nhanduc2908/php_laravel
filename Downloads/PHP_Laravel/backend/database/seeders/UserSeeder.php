<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@security.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Super@123456'),
                'role_id' => 1,
                'avatar' => null,
                'is_active' => true,
                'two_factor_secret' => null,
                'two_factor_enabled' => false,
                'last_login_at' => null,
                'last_login_ip' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Admin User',
                'email' => 'admin@security.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@123456'),
                'role_id' => 2,
                'avatar' => null,
                'is_active' => true,
                'two_factor_secret' => null,
                'two_factor_enabled' => false,
                'last_login_at' => null,
                'last_login_ip' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Security Officer',
                'email' => 'security@security.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Security@123456'),
                'role_id' => 3,
                'avatar' => null,
                'is_active' => true,
                'two_factor_secret' => null,
                'two_factor_enabled' => false,
                'last_login_at' => null,
                'last_login_ip' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Viewer User',
                'email' => 'viewer@security.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Viewer@123456'),
                'role_id' => 4,
                'avatar' => null,
                'is_active' => true,
                'two_factor_secret' => null,
                'two_factor_enabled' => false,
                'last_login_at' => null,
                'last_login_ip' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Auditor User',
                'email' => 'auditor@security.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Auditor@123456'),
                'role_id' => 5,
                'avatar' => null,
                'is_active' => true,
                'two_factor_secret' => null,
                'two_factor_enabled' => false,
                'last_login_at' => null,
                'last_login_ip' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}