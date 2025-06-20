<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $items = Reservation::paginate(10);
        $columns = ['reservation_id', 'pesel', 'plate_number', 'start_time', 'end_time', 'status'];

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
            'routePrefix' => 'reservations',
            'title' => 'Lista rezerwacji',
        ]);
    }


    public function create()
    {
        $columns = ['pesel', 'plate_number', 'start_time', 'end_time'];

        $extraData = [
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),

            'cars' => Car::pluck('plate_number', 'plate_number')->toArray(),
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
        ]);

        if (!$this->isCarAvailable($validated['plate_number'], $validated['start_time'], $validated['end_time'])) {
            return redirect()->back()
                ->withErrors(['plate_number' => 'Pojazd jest już zarezerwowany w tym terminie.'])
                ->withInput();
        }

        $validated['status'] = $this->getStatusByTime($validated['start_time'], $validated['end_time']);

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
        ]);

        if (!$this->isCarAvailable($validated['plate_number'], $validated['start_time'], $validated['end_time'], $reservation_id)) {
            return redirect()->back()
                ->withErrors(['plate_number' => 'Pojazd jest już zarezerwowany w tym terminie.'])
                ->withInput();
        }

        $reservation = Reservation::findOrFail($reservation_id);

        if ($reservation->status === 'completed') {
            $validated['status'] = 'completed';
        } else {
            $validated['status'] = $this->getStatusByTime($validated['start_time'], $validated['end_time']);
        }

        Reservation::where('reservation_id', $reservation_id)->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Rezerwacja zaktualizowana.');
    }

    public function destroy($reservation_id)
    {
        Reservation::where('reservation_id', $reservation_id)->delete();
        return redirect()->route('reservations.index')->with('success', 'Rezerwacja usunięta.');
    }

    public function isCarAvailable($plate_number, $start_time, $end_time, $excludeReservationId = null)
    {
        $query = Reservation::where('plate_number', $plate_number)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                    ->orWhereBetween('end_time', [$start_time, $end_time])
                    ->orWhere(function ($q) use ($start_time, $end_time) {
                        $q->where('start_time', '<=', $start_time)
                            ->where('end_time', '>=', $end_time);
                    });
            });

        if ($excludeReservationId) {
            $query->where('reservation_id', '<>', $excludeReservationId);
        }

        return !$query->exists();
    }

    private function getStatusByTime(string $start_time, string $end_time): string
    {
        $now = Carbon::now();
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);

        if ($now->lt($start)) {
            return 'reserved';
        }
        elseif ($now->between($start, $end)) {
            return 'in_progress';
        }
        else {
            return 'completed';
        }
    }

    public function finish($reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);

        $reservation->end_time = Carbon::now();
        $reservation->status = 'completed';
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Rezerwacja zakończona.');
    }
}