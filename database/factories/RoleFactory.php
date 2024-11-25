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

    public function hidden(bool $hidden = true)
    {
        return $this->state(function (array $attributes) use ($hidden) {
            return [
                'hidden' => $hidden,
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 1,
                'display_name' => 'Admin',
                'code' => 'admin',
                'hidden' => true,
            ];
        });
    }

    public function student()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 2,
                'display_name' => 'Student',
                'code' => 'student',
                'hidden' => false,
            ];
        });
    }

    public function teacher()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 3,
                'display_name' => 'Teacher',
                'code' => 'teacher',
                'hidden' => false,
            ];
        });
    }
}
