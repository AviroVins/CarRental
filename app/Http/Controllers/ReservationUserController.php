<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use App\Models\Rental;
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

        foreach ($items as $reservation) {
            if ($reservation->status !== 'completed') {
                $calculatedStatus = $this->getStatusByTime($reservation->start_time, $reservation->end_time);

                if ($reservation->status !== $calculatedStatus) {
                    $reservation->status = $calculatedStatus;
                    $reservation->save();
                }
            }
        }

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

        $extraData = ['cars' => $cars];

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

        $car = Car::find($request->plate_number);

        if ($car->status !== 'available') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['plate_number' => "Samochód jest obecnie niedostępny do rezerwacji (status: {$car->status})."]);
        }

        $data = $request->only(['plate_number', 'start_time', 'end_time']);
        $data['pesel'] = Auth::user()->pesel;
        $data['status'] = 'reserved';

        DB::table('reservations')->insert($data);

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została dodana.');
    }

    public function edit(Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        // Zabezpieczenie - użytkownik może edytować tylko swoje rezerwacje
        if ($reservation->pesel !== $userPesel) {
            abort(403);
        }

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

        // Zabezpieczenie - użytkownik może edytować tylko swoje rezerwacje
        if ($reservation->pesel !== $userPesel) {
            abort(403);
        }

        $request->validate([
            'plate_number' => 'required|string|exists:cars,plate_number',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $car = Car::find($request->plate_number);

        if ($car->status !== 'available') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['plate_number' => "Samochód jest obecnie niedostępny do rezerwacji (status: {$car->status})."]);
        }

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

        if ($reservation->pesel !== $userPesel) {
            abort(403);
        }

        DB::table('reservations')->where('reservation_id', $reservation->reservation_id)->delete();

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została usunięta.');
    }

    public function finish(Reservation $reservation)
    {
        $userPesel = Auth::user()->pesel;

        if ($reservation->pesel !== $userPesel) {
            abort(403);
        }

        if ($reservation->status !== 'in_progress') {
            return redirect()->route('user.reservations.index')->with('error', 'Tylko aktywne rezerwacje można zakończyć.');
        }

        $reservation->end_time = Carbon::now();
        $reservation->status = 'completed';
        $reservation->save();

        $car = Car::find($reservation->plate_number);

        if ($car && $car->rate > 0) {
            $pickup = \Carbon\Carbon::parse($reservation->start_time);
            $return = \Carbon\Carbon::parse($reservation->end_time);
            
            $diffInHours = ceil($pickup->diffInMinutes($return) / 60);
            $hours = max(1, $diffInHours);
            $cost = round($car->rate * $hours, 2);
            
            Rental::create([
                'reservation_id' => $reservation->reservation_id,
                'pesel' => $reservation->pesel,
                'plate_number' => $reservation->plate_number,
                'pickup_time' => $pickup,
                'return_time' => $return,
                'distance_km' => rand(5, 120),
                'cost' => $cost,
            ]);
        }

        return redirect()->route('user.reservations.index')->with('success', 'Rezerwacja została zakończona.');
    }

    private function getStatusByTime(string $start_time, string $end_time): string
    {
        $now = Carbon::now();
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);

        if ($now->lt($start)) {
            return 'reserved';
        } elseif ($now->between($start, $end)) {
            return 'in_progress';
        } else {
            return 'completed';
        }
    }

}