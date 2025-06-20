<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Rental;
use App\Models\Reservation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userPesel = Auth::user()->pesel;
        $rentals = Rental::where('pesel', $userPesel)
            ->get(['pickup_time', 'return_time']);

        $rentalDays = collect();

        foreach ($rentals as $rental) {
            $start = Carbon::parse($rental->pickup_time)->startOfDay();
            $end = Carbon::parse($rental->return_time)->startOfDay();

            while ($start->lte($end)) {
                $rentalDays->push($start->toDateString());
                $start->addDay();
            }
        }

        $rentalDays = $rentalDays->unique()->values();

        $pastStats = Rental::join('cars', 'rentals.plate_number', '=', 'cars.plate_number')
            ->select('cars.model', DB::raw('count(*) as count'))
            ->groupBy('cars.model')
            ->orderByDesc('count')
            ->get();

        $futureStats = Reservation::join('cars', 'reservations.plate_number', '=', 'cars.plate_number')
            ->where('reservations.end_time', '>=', now())
            ->select('cars.model', DB::raw('count(*) as count'))
            ->groupBy('cars.model')
            ->orderByDesc('count')
            ->get();

        return view('dashboard', [
            'pastLabels' => $pastStats->pluck('model'),
            'pastData' => $pastStats->pluck('count'),
            'futureLabels' => $futureStats->pluck('model'),
            'futureData' => $futureStats->pluck('count'),
            'rentalDays' => $rentalDays,
        ]);
    }
}