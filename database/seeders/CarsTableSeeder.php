<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CarsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $i) {
            DB::table('cars')->insert([
                'plate_number' => strtoupper($faker->bothify('??#####')),
                'maker' => $faker->randomElement(['Tesla', 'Nissan', 'BMW', 'Renault', 'Hyundai']),
                'model' => $faker->word,
                'year' => $faker->numberBetween(2018, 2024),
            ]);
        }
    }
}
