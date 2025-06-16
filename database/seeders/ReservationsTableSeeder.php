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
        $pesels = DB::table('users')->pluck('pesel')->toArray();
        $plates = DB::table('cars')->pluck('plate_number')->toArray();
        
        foreach (range(1, 80) as $i) {
            $start = $faker->dateTimeBetween('-3 days', '+3 days');
            DB::table('reservations')->insert([
                'pesel' => $faker->randomElement($pesels),
                'plate_number' => $faker->randomElement($plates),
                'start_time' => $start,
                'end_time' => (clone $start)->modify('+'.rand(1,4).' hours'),
                'status' => $faker->randomElement(['reserved', 'cancelled', 'completed']),
            ]);
        }
    }
}