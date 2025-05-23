<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate_number' => strtoupper($this->faker->bothify('??#####')),
            'brand' => $this->faker->randomElement(['Tesla', 'BMW', 'Nissan', 'Hyundai']),
            'model' => $this->faker->word(),
            'year' => $this->faker->numberBetween(2018, 2024),
        ];
    }
}