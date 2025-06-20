<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Database\Factories\Providers\PeselProvider;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $this->faker->addProvider(new PeselProvider($this->faker));

        return [
            'pesel' => $this->faker->unique()->pesel(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'phone_number' => $this->faker->phoneNumber,
            'has_driver_license' => $this->faker->boolean(),
            'account_status' => $this->faker->randomElement(['active', 'inactive']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}