<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RentalFactory extends Factory
{
    public function definition(): array
    {
        $pickup = $this->faker->dateTimeBetween('-40 days', '-1 days');
        return [
            'reservation_id' => \App\Models\Reservation::factory(),
            'pesel' => \App\Models\User::factory(),
            'plate_number' => \App\Models\Car::factory()->create()->plate_number,
            'pickup_time' => $pickup,
            'return_time' => (clone $pickup)->modify('+'.rand(1,72).' hours'),
            'distance_km' => $this->faker->numberBetween(5, 100),
            'cost' => $this->faker->randomFloat(2, 20, 470),
        ];
    }
}
