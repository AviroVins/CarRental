<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-3 days', '+3 days');
        return [
            'user_id' => \App\Models\User::factory(),
            'plate_number' => \App\Models\Car::factory()->create()->plate_number,
            'start_time' => $start,
            'end_time' => (clone $start)->modify('+' . rand(1, 4) . ' hours'),
            'status' => $this->faker->randomElement(['reserved', 'cancelled', 'completed']),
        ];
    }
}