<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function admin(): array
    {
        return [
            'id' => 1,
            'display_name' => 'Admin',
            'code' => 'admin',
        ];
    }

    public function student(): array
    {
        return [
            'id' => 2,
            'display_name' => 'Student',
            'code' => 'student',
        ];
    }

    public function teacher(): array
    {
        return [
            'id' => 3,
            'display_name' => 'Teacher',
            'code' => 'teacher',
        ];
    }
}
