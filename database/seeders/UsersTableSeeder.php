<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@car.com',
            'password_hash' => 'admin123',
            'phone_number' => '123456789',
            'has_driver_license' => true,
            'account_status' => 'active',
            'role' => 'admin',
        ]);

        User::create([
            'first_name' => 'Mateusz',
            'last_name' => 'Test',
            'email' => 'test@car.com',
            'password_hash' => 'mateusz123',
            'phone_number' => '123456788',
            'has_driver_license' => true,
            'account_status' => 'active',
            'role' => 'user',
        ]);

        foreach (range(1, 10) as $i) {
            User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password_hash' => 'password',
                'phone_number' => $faker->phoneNumber,
                'has_driver_license' => $faker->boolean,
                'account_status' => $faker->randomElement(['active', 'inactive', 'blocked']),
                'role' => 'user',
            ]);
        }

    }
}