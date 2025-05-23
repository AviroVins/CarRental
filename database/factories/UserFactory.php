<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password_hash' => Hash::make('password'),
            'phone_number' => $this->faker->phoneNumber,
            'has_driver_license' => $this->faker->boolean(),
            'account_status' => $this->faker->randomElement(['active', 'inactive', 'blocked']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}