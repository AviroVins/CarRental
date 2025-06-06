<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationUserController extends Controller
{
    public function index()
    {
        $userPesel = Auth::user()->pesel;

        $items = Reservation::where('pesel', $userPesel)->get();
        $columns = ['reservation_id', 'plate_number', 'start_time', 'end_time', 'status'];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'user.reservations',
            'title' => 'Moje rezerwacje',
        ]);
    }

    public function create()
    {
        $columns = ['plate_number', 'start_time', 'end_time', 'status'];

        $cars = Car::all()->mapWithKeys(function ($car) {
            return [
                $car->plate_number => "{$car->plate_number} - {$car->make} {$car->model}",
            ];
        });

        $extraData = [
            'cars' => $cars,
            'statuses' => ['pending', 'confirmed', 'cancelled'],
        ];

        return view('shared.form', [
            'item' => new Reservation(),
            'columns' => $columns,
            'routePrefix' => 'user.reservations',
            'mode' => 'create',
            'title' => 'Dodaj rezerwację',
            'extraData' => $extraData,
        ]);
    }

    public function edit(Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        $columns = ['plate_number', 'start_time', 'end_time', 'status'];

        $cars = Car::all()->mapWithKeys(function ($car) {
            return [
                $car->plate_number => "{$car->plate_number} - {$car->make} {$car->model}",
            ];
        });

        $extraData = [
            'cars' => $cars,
            'statuses' => ['pending', 'confirmed', 'cancelled'],
        ];

        return view('shared.form', [
            'item' => $reservation,
            'columns' => $columns,
            'routePrefix' => 'user.reservations',
            'mode' => 'edit',
            'title' => 'Edytuj rezerwację',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'plate_number' => 'required|string|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|string|in:pending,confirmed,cancelled',
        ]);

        $data = $request->only(['plate_number', 'start_time', 'end_time', 'status']);
        $data['pesel'] = Auth::user()->pesel;

        DB::table('reservations')->insert($data);

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została dodana.');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        $request->validate([
            'plate_number' => 'required|string|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|string|in:pending,confirmed,cancelled',
        ]);

        $data = $request->only(['plate_number', 'start_time', 'end_time', 'status']);
        $data['pesel'] = $userPesel;

        DB::table('reservations')
            ->where('reservation_id', $reservation->reservation_id)
            ->update($data);

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została zaktualizowana.');
    }

    public function destroy(Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        DB::table('reservations')->where('reservation_id', $reservation->reservation_id)->delete();

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została usunięta.');
    }
}