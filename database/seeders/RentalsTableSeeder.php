<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalsTableSeeder extends Seeder
{
    public function run(): void
    {
        $completedReservations = DB::table('reservations')
            ->where('status', 'completed')
            ->get();

        foreach ($completedReservations as $reservation) {
            $car = DB::table('cars')->where('plate_number', $reservation->plate_number)->first();

            if (!$car || $car->rate <= 0) {
                continue;
            }

            $pickup = Carbon::parse($reservation->start_time);
            $return = Carbon::parse($reservation->end_time);

            $diffInHours = ceil($pickup->diffInMinutes($return) / 60);
            $hours = max(1, $diffInHours);

            $cost = round($car->rate * $hours, 2);


            DB::table('rentals')->insert([
                'reservation_id' => $reservation->reservation_id,
                'pesel' => $reservation->pesel,
                'plate_number' => $reservation->plate_number,
                'pickup_time' => $pickup,
                'return_time' => $return,
                'distance_km' => rand(10, 150),
                'cost' => $cost,
            ]);
        }
    }
}
