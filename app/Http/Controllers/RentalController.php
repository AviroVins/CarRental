<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $items = Rental::paginate(10);
        $columns = ['rental_id', 'reservation_id', 'pesel', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];
        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'rentals',
            'title' => 'Lista wypożyczeń',
        ]);
    }

    public function create()
    {
        $rentedReservationIds = Rental::pluck('reservation_id')->toArray();

        $reservations = Reservation::where('status', 'completed')
            ->whereNotIn('reservation_id', $rentedReservationIds)
            ->get()
            ->mapWithKeys(function ($reservation) {
                return [
                    $reservation->reservation_id => "[" . $reservation->reservation_id . "] "
                    . $reservation->plate_number . " (" . $reservation->pesel . ")",
                ];
            });
        
        $extraData = [
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
            'reservations' => $reservations,
            'cars' => Car::pluck('plate_number', 'plate_number')->toArray(),
        ];

        return view('shared.form', [
            'item' => new Rental(),
            'routePrefix' => 'rentals',
            'columns' => ['reservation_id', 'pesel', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'],
            'title' => 'Dodaj wypożyczenie',
            'mode' => 'create',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'distance_km' => 'nullable|integer|min:0',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);

        if ($reservation->status !== 'completed') {
            return redirect()->back()->with('error', 'Można utworzyć wypożyczenie tylko z zakończonej rezerwacji.');
        }

        $car = Car::where('plate_number', $reservation->plate_number)->first();

        if (!$car) {
            return redirect()->back()->with('error', 'Nie znaleziono samochodu powiązanego z rezerwacją.');
        }

        $pickup_time = $reservation->start_time;
        $return_time = $reservation->end_time;

        $start = Carbon::parse($pickup_time);
        $end = Carbon::parse($return_time);

        $hours = ceil($end->floatDiffInHours($start));
        $cost = $car->rate * $hours;

        Rental::create([
            'reservation_id' => $reservation->reservation_id,
            'pesel' => $reservation->pesel,
            'plate_number' => $reservation->plate_number,
            'pickup_time' => $pickup_time,
            'return_time' => $return_time,
            'distance_km' => $request->distance_km ?? 0,
            'cost' => round($cost, 2),
        ]);

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie zostało dodane.');
    }

    public function edit(Rental $rental)
    {
        $columns = ['rental_id', 'reservation_id', 'pesel', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

        $reservations = Reservation::where('status', 'completed')->get()
            ->mapWithKeys(fn($r) => [$r->reservation_id => "[" . $r->reservation_id . "] " . $r->plate_number . " (" . $r->pesel . ")"]);

        $extraData = [
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
            'reservations' => $reservations,
            'cars' => Car::pluck('plate_number', 'plate_number')->toArray(),
        ];

        return view('shared.form', [
            'item' => $rental,
            'routePrefix' => 'rentals',
            'columns' => $columns,
            'title' => 'Edytuj wypożyczenie',
            'mode' => 'edit',
            'extraData' => $extraData,
        ]);
    }

    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'distance_km' => 'nullable|integer|min:0',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);
        $car = Car::where('plate_number', $reservation->plate_number)->first();

        if (!$car) {
            return redirect()->back()->with('error', 'Nie znaleziono samochodu powiązanego z rezerwacją.');
        }

        $pickup_time = $reservation->start_time;
        $return_time = $reservation->end_time;

        $start = Carbon::parse($pickup_time);
        $end = Carbon::parse($return_time);

        $hours = ceil($end->floatDiffInHours($start));
        $cost = $car->rate * $hours;

        $rental->update([
            'reservation_id' => $reservation->reservation_id,
            'pesel' => $reservation->pesel,
            'plate_number' => $reservation->plate_number,
            'pickup_time' => $pickup_time,
            'return_time' => $return_time,
            'distance_km' => $request->distance_km ?? 0,
            'cost' => round($cost, 2),
        ]);

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie zostało zaktualizowane.');
    }

    public function destroy(Rental $rental)
    {
        $rental->delete();

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie zostało usunięte.');
    }
}