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

        DB::table('cars')->insert([
            [
                'plate_number' => 'WW00001',
                'maker' => 'Tesla',
                'model' => 'Model Y',
                'year' => 2023,
            ],
            [
                'plate_number' => 'WW00002',
                'maker' => 'Tesla',
                'model' => 'Model 3',
                'year' => 2022,
            ],
            [
                'plate_number' => 'WW00003',
                'maker' => 'Audi',
                'model' => 'Q4 e-tron',
                'year' => 2023,
            ],
            [
                'plate_number' => 'WW00004',
                'maker' => 'Kia',
                'model' => 'Niro EV',
                'year' => 2022,
            ],
            [
                'plate_number' => 'WW00005',
                'maker' => 'Volkswagen',
                'model' => 'ID.4',
                'year' => 2023,
            ],
            [
                'plate_number' => 'WW00006',
                'maker' => 'Volvo',
                'model' => 'EX30',
                'year' => 2024,
            ],
            [
                'plate_number' => 'WW00007',
                'maker' => 'Mercedes',
                'model' => 'EQA',
                'year' => 2022,
            ],
            [
                'plate_number' => 'WW00008',
                'maker' => 'Nissan',
                'model' => 'Leaf',
                'year' => 2023,
            ],
            [
                'plate_number' => 'WW00009',
                'maker' => 'Kia',
                'model' => 'EV6',
                'year' => 2021,
            ],
        ]);
    }
}
