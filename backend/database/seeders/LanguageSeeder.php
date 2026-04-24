<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $translations = [
            // English
            ['locale' => 'en', 'key' => 'welcome', 'value' => 'Welcome to Security Assessment Platform', 'group' => 'messages'],
            ['locale' => 'en', 'key' => 'login', 'value' => 'Login', 'group' => 'auth'],
            ['locale' => 'en', 'key' => 'logout', 'value' => 'Logout', 'group' => 'auth'],
            ['locale' => 'en', 'key' => 'dashboard', 'value' => 'Dashboard', 'group' => 'navigation'],
            ['locale' => 'en', 'key' => 'users', 'value' => 'Users', 'group' => 'navigation'],
            ['locale' => 'en', 'key' => 'servers', 'value' => 'Servers', 'group' => 'navigation'],
            ['locale' => 'en', 'key' => 'assessments', 'value' => 'Assessments', 'group' => 'navigation'],
            ['locale' => 'en', 'key' => 'reports', 'value' => 'Reports', 'group' => 'navigation'],
            ['locale' => 'en', 'key' => 'settings', 'value' => 'Settings', 'group' => 'navigation'],
            
            // Vietnamese
            ['locale' => 'vi', 'key' => 'welcome', 'value' => 'Chào mừng đến với Nền tảng Đánh giá An ninh', 'group' => 'messages'],
            ['locale' => 'vi', 'key' => 'login', 'value' => 'Đăng nhập', 'group' => 'auth'],
            ['locale' => 'vi', 'key' => 'logout', 'value' => 'Đăng xuất', 'group' => 'auth'],
            ['locale' => 'vi', 'key' => 'dashboard', 'value' => 'Bảng điều khiển', 'group' => 'navigation'],
            ['locale' => 'vi', 'key' => 'users', 'value' => 'Người dùng', 'group' => 'navigation'],
            ['locale' => 'vi', 'key' => 'servers', 'value' => 'Máy chủ', 'group' => 'navigation'],
            ['locale' => 'vi', 'key' => 'assessments', 'value' => 'Đánh giá', 'group' => 'navigation'],
            ['locale' => 'vi', 'key' => 'reports', 'value' => 'Báo cáo', 'group' => 'navigation'],
            ['locale' => 'vi', 'key' => 'settings', 'value' => 'Cài đặt', 'group' => 'navigation'],
            
            // Japanese
            ['locale' => 'ja', 'key' => 'welcome', 'value' => 'セキュリティ評価プラットフォームへようこそ', 'group' => 'messages'],
            ['locale' => 'ja', 'key' => 'login', 'value' => 'ログイン', 'group' => 'auth'],
            ['locale' => 'ja', 'key' => 'logout', 'value' => 'ログアウト', 'group' => 'auth'],
            ['locale' => 'ja', 'key' => 'dashboard', 'value' => 'ダッシュボード', 'group' => 'navigation'],
            ['locale' => 'ja', 'key' => 'users', 'value' => 'ユーザー', 'group' => 'navigation'],
            ['locale' => 'ja', 'key' => 'servers', 'value' => 'サーバー', 'group' => 'navigation'],
            ['locale' => 'ja', 'key' => 'assessments', 'value' => '評価', 'group' => 'navigation'],
            ['locale' => 'ja', 'key' => 'reports', 'value' => 'レポート', 'group' => 'navigation'],
            ['locale' => 'ja', 'key' => 'settings', 'value' => '設定', 'group' => 'navigation'],
        ];
        
        DB::table('translations')->insert($translations);
    }
}