<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $rentalIds = DB::table('rentals')->pluck('rental_id')->toArray();
        $userIds = DB::table('users')->pluck('user_id')->toArray();

        foreach (range(1, 5) as $i) {
            DB::table('payments')->insert([
                'rental_id' => $faker->randomElement($rentalIds),
                'user_id' => $faker->randomElement($userIds),
                'amount' => $faker->numberBetween(30, 200),
                'status' => $faker->randomElement(['pending', 'paid']),
                'method' => $faker->randomElement(['card', 'cash', 'blik']),
            ]);
        }
    }
}