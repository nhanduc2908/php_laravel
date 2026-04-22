<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            CategorySeeder::class,
            CriteriaSeeder::class,
            UserSeeder::class,
            ServerSeeder::class,
            AssessmentFileSeeder::class,
            MenuSeeder::class,
            LanguageSeeder::class,
            TestDataSeeder::class,
            WrapperSeeder::class,
            TestSeeder::class,
        ]);
    }
}