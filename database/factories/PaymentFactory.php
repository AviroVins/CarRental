<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rental_id' => \App\Models\Rental::factory(),
            'pesel' => \App\Models\User::factory(),
            'amount' => $this->faker->randomFloat(2, 30, 200),
            'status' => $this->faker->randomElement(['pending', 'paid']),
            'method' => $this->faker->randomElement(['card', 'blik']),
        ];
    }
}