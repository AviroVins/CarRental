<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void {
        $this->call([
        UsersTableSeeder::class,
        CarsTableSeeder::class,
        ReservationsTableSeeder::class,
        RentalsTableSeeder::class,
        PaymentsTableSeeder::class,
    ]);
    }
}