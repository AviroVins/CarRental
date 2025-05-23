<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $i) {
            DB::table('users')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password_hash' => Hash::make('password'),
                'phone_number' => $faker->phoneNumber,
                'has_driver_license' => $faker->boolean,
                'account_status' => $faker->randomElement(['active', 'inactive', 'blocked']),
            ]);
        }
    }
}