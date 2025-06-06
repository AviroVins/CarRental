<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RentalsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $reservationIds = DB::table('reservations')->pluck('reservation_id')->toArray();
        $pesels = DB::table('users')->pluck('pesel')->toArray();
        $plates = DB::table('cars')->pluck('plate_number')->toArray();

        foreach (range(1, 5) as $i) {
            $pickup = $faker->dateTimeBetween('-7 days', '-1 days');
            DB::table('rentals')->insert([
                'reservation_id' => $faker->randomElement($reservationIds),
                'pesel' => $faker->randomElement($pesels),
                'plate_number' => $faker->randomElement($plates),
                'pickup_time' => $pickup,
                'return_time' => (clone $pickup)->modify('+'.rand(1,3).' hours'),
                'distance_km' => $faker->numberBetween(5, 100),
                'cost' => $faker->numberBetween(20, 150),
            ]);
        }
    }
}