<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\RoleCode;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function password(string $password)
    {
        return $this->state(fn(array $attributes) => [
            'password' => Hash::make($password),
        ]);
    }

    public function email(string $email)
    {
        return $this->state(fn(array $attributes) => [
            'email' => $email,
        ]);
    }

    public function name(string $name)
    {
        return $this->state(fn(array $attributes) => [
            'name' => $name,
        ]);
    }

    public function admin()
    {
        $adminRoleId = Role::whereCode(RoleCode::Admin)->first(['id'])->id;

        return $this->state(fn(array $attributes) => [
            'role_id' => $adminRoleId,
        ]);
    }
}
