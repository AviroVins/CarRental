<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ReservationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $pesels = DB::table('users')->pluck('pesel')->toArray();
        $plates = DB::table('cars')->pluck('plate_number')->toArray();

        $getStatusByTime = function ($start, $end) {
            $now = Carbon::now();
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);

            if ($now->lt($start)) {
                return 'reserved';
            } elseif ($now->between($start, $end)) {
                return 'in_progress';
            } else {
                return 'completed';
            }
        };

        // Tablica do śledzenia zarezerwowanych przedziałów czasowych dla każdego auta (by uniknąć nakładania się)
        $reservedSlots = [];

        foreach (range(1, 300) as $i) {
            $plate = $faker->randomElement($plates);

            // Znajdź losowy wolny slot dla tego auta
            do {
                $start = $faker->dateTimeBetween('-14 days', '+50 days');
                $durationHours = rand(1, 4);
                $end = (clone $start)->modify("+{$durationHours} hours");

                $conflict = false;
                if (isset($reservedSlots[$plate])) {
                    foreach ($reservedSlots[$plate] as $slot) {
                        if (
                            ($start >= $slot['start'] && $start < $slot['end']) ||
                            ($end > $slot['start'] && $end <= $slot['end']) ||
                            ($start <= $slot['start'] && $end >= $slot['end'])
                        ) {
                            $conflict = true;
                            break;
                        }
                    }
                }
            } while ($conflict);

            $reservedSlots[$plate][] = ['start' => $start, 'end' => $end];

            $status = $getStatusByTime($start, $end);
            $pesel = $faker->randomElement($pesels);

            DB::table('reservations')->insert([
                'pesel' => $pesel,
                'plate_number' => $plate,
                'start_time' => $start,
                'end_time' => $end,
                'status' => $status,
            ]);
        }
    }
}
