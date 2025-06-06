<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rental;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        // Przeszłe wynajmy (rentals)
        $pastStats = Rental::join('cars', 'rentals.plate_number', '=', 'cars.plate_number')
            ->select('cars.model', DB::raw('count(*) as count'))
            ->groupBy('cars.model')
            ->orderByDesc('count')
            ->get();

        $pastLabels = $pastStats->pluck('model')->toArray();
        $pastData = $pastStats->pluck('count')->toArray();

        // Bieżące i przyszłe rezerwacje (reservations)
        $futureStats = Reservation::join('cars', 'reservations.plate_number', '=', 'cars.plate_number')
            ->where('reservations.end_time', '>=', now())
            ->select('cars.model', DB::raw('count(*) as count'))
            ->groupBy('cars.model')
            ->orderByDesc('count')
            ->get();

        $futureLabels = $futureStats->pluck('model')->toArray();
        $futureData = $futureStats->pluck('count')->toArray();

        return view('dashboard', [
            'pastLabels' => $pastLabels,
            'pastData' => $pastData,
            'futureLabels' => $futureLabels,
            'futureData' => $futureData,
        ]);
    }
}
