<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolYear>
 */
class SchoolYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = now()->year;
        return [
            'year_start' => $now,
            'year_end' => $now + 1,
        ];
    }

    public function yearStart(int $yearStart)
    {
        return $this->state(fn(array $attributes) => [
            'year_start' => $yearStart,
            'year_end' => $yearStart + 1,
        ]);
    }
}
