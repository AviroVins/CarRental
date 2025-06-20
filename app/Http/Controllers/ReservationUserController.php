<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationUserController extends Controller
{
    public function index()
    {
        $userPesel = Auth::user()->pesel;

        $items = Reservation::where('pesel', $userPesel)->with('car')->paginate(10);
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
        $columns = ['plate_number', 'start_time', 'end_time'];

        $cars = Car::all()->mapWithKeys(function ($car) {
            return [
                $car->plate_number => "{$car->plate_number} - {$car->make} {$car->model}",
            ];
        });

        $extraData = [
            'cars' => $cars,
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

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $data = $request->only(['plate_number', 'start_time', 'end_time']);
        $data['pesel'] = Auth::user()->pesel;
        $data['status'] = 'reserved';

        DB::table('reservations')->insert($data);

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została dodana.');
    }

    public function edit(Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        $columns = ['plate_number', 'start_time', 'end_time'];

        $cars = Car::all()->mapWithKeys(function ($car) {
            return [
                $car->plate_number => "{$car->plate_number} - {$car->make} {$car->model}",
            ];
        });

        $extraData = [
            'cars' => $cars,
            'current_status' => $reservation->status,
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

    public function update(Request $request, Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        $request->validate([
            'plate_number' => 'required|string|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $data = $request->only(['plate_number', 'start_time', 'end_time']);
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

    public function finish(Reservation $reservation)
    {
        if ($reservation->status !== 'in_progress') {
            return redirect()->route('user.reservations.index')->with('error', 'Tylko aktywne rezerwacje można zakończyć.');
        }

        $reservation->end_time = Carbon::now();
        $reservation->status = 'completed';
        $reservation->save();

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została zakończona.');
    }
}