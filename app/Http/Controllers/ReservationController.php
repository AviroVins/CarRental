<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $items = Reservation::paginate(10);
        $columns = ['reservation_id', 'pesel', 'plate_number', 'start_time', 'end_time', 'status'];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'reservations',
            'title' => 'Lista rezerwacji',
        ]);
    }

    public function create()
    {
        $columns = ['pesel', 'plate_number', 'start_time', 'end_time', 'status'];

        $extraData = [
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),

            'cars' => Car::pluck('plate_number', 'plate_number')->toArray(),

            'statuses' => ['reserved', 'in_progress', 'completed'],
        ];

        return view('shared.form', [
            'item' => new Reservation(),
            'columns' => $columns,
            'routePrefix' => 'reservations',
            'mode' => 'create',
            'title' => 'Dodaj rezerwację',
            'extraData' => $extraData,
        ]);
    }

    public function edit(Reservation $reservation)
    {
        $columns = ['pesel', 'plate_number', 'start_time', 'end_time', 'status'];

        $extraData = [
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),

            'cars' => Car::pluck('plate_number', 'plate_number')->toArray(),

            'statuses' => ['reserved', 'in_progress', 'completed'],
        ];

        return view('shared.form', [
            'item' => $reservation,
            'columns' => $columns,
            'routePrefix' => 'reservations',
            'mode' => 'edit',
            'title' => 'Edytuj rezerwację',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesel' => 'required|exists:users,pesel',
            'plate_number' => 'required|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'status' => 'required|in:reserved,in_progress,completed',
        ]);

        Reservation::create($validated);

        return redirect()->route('reservations.index')->with('success', 'Rezerwacja dodana.');
    }

    public function update(Request $request, $reservation_id)
    {
        $validated = $request->validate([
            'pesel' => 'required|exists:users,pesel',
            'plate_number' => 'required|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'status' => 'required|in:reserved,in_progress,completed',
        ]);

        Reservation::where('reservation_id', $reservation_id)->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Rezerwacja zaktualizowana.');
    }

    public function destroy($reservation_id)
    {
        Reservation::where('reservation_id', $reservation_id)->delete();
        return redirect()->route('reservations.index')->with('success', 'Rezerwacja usunięta.');
    }
}