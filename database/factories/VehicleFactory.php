<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate_number' => strtoupper(fake()->randomLetter()) . fake()->numberBetween(100, 999),
        ];
    }
}
