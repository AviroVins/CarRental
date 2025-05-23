<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReservationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = DB::table('users')->pluck('user_id')->toArray();
        $plates = DB::table('cars')->pluck('plate_number')->toArray();

        foreach (range(1, 5) as $i) {
            $start = $faker->dateTimeBetween('-3 days', '+3 days');
            DB::table('reservations')->insert([
                'user_id' => $faker->randomElement($userIds),
                'plate_number' => $faker->randomElement($plates),
                'start_time' => $start,
                'end_time' => (clone $start)->modify('+'.rand(1,4).' hours'),
                'status' => $faker->randomElement(['reserved', 'cancelled', 'completed']),
            ]);
        }
    }
}