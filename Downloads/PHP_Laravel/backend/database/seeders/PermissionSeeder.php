<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User permissions
            ['name' => 'Xem người dùng', 'slug' => 'view_users', 'resource' => 'users', 'action' => 'view'],
            ['name' => 'Thêm người dùng', 'slug' => 'create_users', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'Sửa người dùng', 'slug' => 'edit_users', 'resource' => 'users', 'action' => 'edit'],
            ['name' => 'Xóa người dùng', 'slug' => 'delete_users', 'resource' => 'users', 'action' => 'delete'],
            
            // Role permissions
            ['name' => 'Xem vai trò', 'slug' => 'view_roles', 'resource' => 'roles', 'action' => 'view'],
            ['name' => 'Quản lý vai trò', 'slug' => 'manage_roles', 'resource' => 'roles', 'action' => 'manage'],
            
            // Server permissions
            ['name' => 'Xem máy chủ', 'slug' => 'view_servers', 'resource' => 'servers', 'action' => 'view'],
            ['name' => 'Quản lý máy chủ', 'slug' => 'manage_servers', 'resource' => 'servers', 'action' => 'manage'],
            ['name' => 'Quét máy chủ', 'slug' => 'scan_servers', 'resource' => 'servers', 'action' => 'scan'],
            
            // Criteria permissions
            ['name' => 'Xem tiêu chí', 'slug' => 'view_criteria', 'resource' => 'criteria', 'action' => 'view'],
            ['name' => 'Quản lý tiêu chí', 'slug' => 'manage_criteria', 'resource' => 'criteria', 'action' => 'manage'],
            
            // Assessment permissions
            ['name' => 'Chạy đánh giá', 'slug' => 'run_assessments', 'resource' => 'assessments', 'action' => 'run'],
            ['name' => 'Xem đánh giá', 'slug' => 'view_assessments', 'resource' => 'assessments', 'action' => 'view'],
            
            // File permissions
            ['name' => 'Xem tệp', 'slug' => 'view_files', 'resource' => 'files', 'action' => 'view'],
            ['name' => 'Tạo tệp', 'slug' => 'create_files', 'resource' => 'files', 'action' => 'create'],
            ['name' => 'Sửa tệp', 'slug' => 'edit_files', 'resource' => 'files', 'action' => 'edit'],
            ['name' => 'Xóa tệp', 'slug' => 'delete_files', 'resource' => 'files', 'action' => 'delete'],
            ['name' => 'Chia sẻ tệp', 'slug' => 'share_files', 'resource' => 'files', 'action' => 'share'],
            
            // Report permissions
            ['name' => 'Xem báo cáo', 'slug' => 'view_reports', 'resource' => 'reports', 'action' => 'view'],
            ['name' => 'Tạo báo cáo', 'slug' => 'create_reports', 'resource' => 'reports', 'action' => 'create'],
            
            // Audit permissions
            ['name' => 'Xem audit log', 'slug' => 'view_audit', 'resource' => 'audit', 'action' => 'view'],
            
            // Dashboard permissions
            ['name' => 'Xem dashboard', 'slug' => 'view_dashboard', 'resource' => 'dashboard', 'action' => 'view'],
            
            // Settings permissions
            ['name' => 'Quản lý cài đặt', 'slug' => 'manage_settings', 'resource' => 'settings', 'action' => 'manage'],
        ];
        
        DB::table('permissions')->insert($permissions);
        
        // Gán permissions cho roles
        // Super Admin có tất cả permissions
        $superAdminId = 1;
        $allPermissionIds = DB::table('permissions')->pluck('id')->toArray();
        foreach ($allPermissionIds as $permId) {
            DB::table('role_permission')->insert([
                'role_id' => $superAdminId,
                'permission_id' => $permId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Admin permissions
        $adminId = 2;
        $adminPerms = ['view_users', 'create_users', 'edit_users', 'delete_users', 'view_roles', 'manage_roles', 
                       'view_servers', 'manage_servers', 'scan_servers', 'view_criteria', 'manage_criteria',
                       'run_assessments', 'view_assessments', 'view_files', 'create_files', 'edit_files', 
                       'delete_files', 'share_files', 'view_reports', 'create_reports', 'view_dashboard', 'manage_settings'];
        foreach ($adminPerms as $slug) {
            $perm = DB::table('permissions')->where('slug', $slug)->first();
            if ($perm) {
                DB::table('role_permission')->insert([
                    'role_id' => $adminId,
                    'permission_id' => $perm->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Security Officer permissions
        $securityId = 3;
        $securityPerms = ['view_servers', 'scan_servers', 'view_criteria', 'run_assessments', 'view_assessments',
                          'view_files', 'create_files', 'edit_files', 'share_files', 'view_reports', 'create_reports', 'view_dashboard'];
        foreach ($securityPerms as $slug) {
            $perm = DB::table('permissions')->where('slug', $slug)->first();
            if ($perm) {
                DB::table('role_permission')->insert([
                    'role_id' => $securityId,
                    'permission_id' => $perm->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Viewer permissions
        $viewerId = 4;
        $viewerPerms = ['view_reports', 'view_dashboard'];
        foreach ($viewerPerms as $slug) {
            $perm = DB::table('permissions')->where('slug', $slug)->first();
            if ($perm) {
                DB::table('role_permission')->insert([
                    'role_id' => $viewerId,
                    'permission_id' => $perm->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Auditor permissions
        $auditorId = 5;
        $auditorPerms = ['view_audit', 'view_reports', 'view_dashboard'];
        foreach ($auditorPerms as $slug) {
            $perm = DB::table('permissions')->where('slug', $slug)->first();
            if ($perm) {
                DB::table('role_permission')->insert([
                    'role_id' => $auditorId,
                    'permission_id' => $perm->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}