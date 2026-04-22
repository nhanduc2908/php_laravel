<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => Role::inRandomOrder()->first()->id ?? 3,
            'avatar' => $this->faker->imageUrl(100, 100, 'people'),
            'is_active' => $this->faker->boolean(90),
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'last_login_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'last_login_ip' => $this->faker->ipv4(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'admin')->first()->id,
        ]);
    }

    public function superAdmin()
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'super_admin')->first()->id,
        ]);
    }
}