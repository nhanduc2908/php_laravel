<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServerSeeder extends Seeder
{
    public function run()
    {
        DB::table('servers')->insert([
            [
                'name' => 'Production Web Server',
                'host' => '192.168.1.10',
                'port' => 22,
                'username' => 'admin',
                'password' => null,
                'ssh_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC...',
                'description' => 'Main production web server hosting core applications',
                'status' => 'active',
                'os_type' => 'Ubuntu 22.04 LTS',
                'last_scan_at' => null,
                'last_connection_test' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Database Server',
                'host' => '192.168.1.20',
                'port' => 22,
                'username' => 'dbadmin',
                'password' => null,
                'ssh_key' => null,
                'description' => 'MySQL database server for production',
                'status' => 'active',
                'os_type' => 'Ubuntu 22.04 LTS',
                'last_scan_at' => null,
                'last_connection_test' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'API Gateway Server',
                'host' => '192.168.1.30',
                'port' => 2222,
                'username' => 'apiadmin',
                'password' => 'TempPass123!',
                'ssh_key' => null,
                'description' => 'API gateway and load balancer',
                'status' => 'active',
                'os_type' => 'CentOS 8',
                'last_scan_at' => null,
                'last_connection_test' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Backup Server',
                'host' => '192.168.1.40',
                'port' => 22,
                'username' => 'backup',
                'password' => null,
                'ssh_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC...',
                'description' => 'Backup and disaster recovery server',
                'status' => 'active',
                'os_type' => 'Debian 11',
                'last_scan_at' => null,
                'last_connection_test' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Development Server',
                'host' => '192.168.2.10',
                'port' => 22,
                'username' => 'dev',
                'password' => 'DevPass123!',
                'ssh_key' => null,
                'description' => 'Development and testing environment',
                'status' => 'pending',
                'os_type' => 'Ubuntu 20.04 LTS',
                'last_scan_at' => null,
                'last_connection_test' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}