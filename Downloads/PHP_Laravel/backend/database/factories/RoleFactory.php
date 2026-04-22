<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'level' => $this->faker->numberBetween(1, 100),
            'is_default' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function superAdmin()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'level' => 100,
            'is_default' => false,
        ]);
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin',
            'slug' => 'admin',
            'level' => 80,
            'is_default' => false,
        ]);
    }

    public function securityOfficer()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Security Officer',
            'slug' => 'security_officer',
            'level' => 60,
            'is_default' => false,
        ]);
    }

    public function viewer()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Viewer',
            'slug' => 'viewer',
            'level' => 40,
            'is_default' => true,
        ]);
    }
}