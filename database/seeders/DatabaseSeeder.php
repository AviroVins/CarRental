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

    // public function run(): void {
    //     \App\Models\User::factory(10)->create();
    //     \App\Models\Car::factory(5)->create();

    //     \App\Models\Reservation::factory(5)->create();
    //     \App\Models\Rental::factory(5)->create();
    //     \App\Models\Payment::factory(5)->create();
    // }
}
