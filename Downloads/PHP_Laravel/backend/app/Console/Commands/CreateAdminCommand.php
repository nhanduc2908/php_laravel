<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'make:admin {email?} {name?}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Enter email address');
        $name = $this->argument('name') ?? $this->ask('Enter name');
        $password = $this->secret('Enter password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => 1, // Super Admin role
            'is_active' => true,
        ]);

        $this->info("Admin user '{$email}' created successfully!");
        return Command::SUCCESS;
    }
}