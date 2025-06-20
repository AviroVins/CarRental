<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    protected $primaryKey = 'rental_id';
    public $incrementing = true;
    protected $keyType = 'int';

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
        $columns = ['reservation_id', 'pesel', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

        $extraData = [
            'reservations' => DB::table('reservations')
                ->select('reservation_id', DB::raw("CONCAT('[', reservation_id, '] ', plate_number, ' (', pesel, ')') AS label"))
                ->pluck('label', 'reservation_id')
                ->toArray(),
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
            'cars' => Car::pluck('plate_number', 'plate_number')->toArray(),
        ];

        return view('shared.form', [
            'item' => new Rental(),
            'routePrefix' => 'rentals',
            'columns' => $columns,
            'title' => 'Dodaj wypożyczenie',
            'mode' => 'create',
            'extraData' => $extraData,
        ]);
    }

    public function edit(Rental $rental)
    {
        $columns = ['rental_id', 'reservation_id', 'pesel', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

        $extraData = [
            'reservations' => DB::table('reservations')
                ->select('reservation_id', DB::raw("CONCAT('[', reservation_id, '] ', plate_number, ' (', pesel, ')') AS label"))
                ->pluck('label', 'reservation_id')
                ->toArray(),
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'pesel' => 'required|exists:users,pesel',
            'plate_number' => 'required|exists:cars,plate_number',
            'pickup_time' => 'required|date',
            'return_time' => 'nullable|date|after_or_equal:pickup_time',
            'distance_km' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
        ]);

        Rental::create($validated);

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie zostało dodane.');
    }

    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'pesel' => 'required|exists:users,pesel',
            'plate_number' => 'required|exists:cars,plate_number',
            'pickup_time' => 'required|date',
            'return_time' => 'nullable|date|after_or_equal:pickup_time',
            'distance_km' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $rental->update($validated);

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie zostało zaktualizowane.');
    }

    public function destroy(Rental $rental)
    {
        $rental->delete();

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie zostało usunięte.');
    }
}