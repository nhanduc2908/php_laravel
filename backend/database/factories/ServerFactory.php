<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{
    protected $model = Server::class;

    public function definition()
    {
        $statuses = ['pending', 'active', 'inactive'];
        $osList = ['Ubuntu 20.04', 'Ubuntu 22.04', 'CentOS 7', 'Debian 11', 'Windows Server 2019'];
        
        return [
            'name' => $this->faker->domainWord() . '-server',
            'host' => $this->faker->ipv4(),
            'port' => $this->faker->numberBetween(22, 2222),
            'username' => $this->faker->userName(),
            'password' => $this->faker->optional()->password(),
            'ssh_key' => $this->faker->optional()->text(200),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement($statuses),
            'os_type' => $this->faker->randomElement($osList),
            'last_scan_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'last_connection_test' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }

    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function withRecentScan()
    {
        return $this->state(fn (array $attributes) => [
            'last_scan_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }
}