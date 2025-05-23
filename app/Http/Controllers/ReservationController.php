<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    protected $primaryKey = 'reservation_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public function index()
    {
        $items = Reservation::all();
        $columns = ['reservation_id', 'user_id', 'plate_number', 'start_time', 'end_time', 'status'];
        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'reservations',
            'title' => 'Lista rezerwacji',
        ]);
    }

    public function create()
    {
        $columns = ['reservation_id', 'user_id', 'plate_number', 'start_time', 'end_time', 'status'];

        return view('shared.form', [
            'item' => new Reservation(),
            'columns' => $columns,
            'routePrefix' => 'reservations',
            'mode' => 'create',
            'title' => 'Dodaj rezerwację',
        ]);
    }

    public function edit(Reservation $reservation)
    {
        $columns = ['reservation_id', 'user_id', 'plate_number', 'start_time', 'end_time', 'status'];

        return view('shared.form', [
            'item' => $reservation,
            'routePrefix' => 'reservations',
            'columns' => $columns,
            'title' => 'Edytuj rezerwację',
            'mode' => 'edit'
        ]);
    }

    public function store(Request $request) {
        DB::table('reservations')->insert($request->except('_token'));
        return redirect()->route('reservations.index');
    }

    public function update(Request $request, $reservation_id) {
        DB::table('reservations')->where('reservation_id', $reservation_id)->update($request->except('_token', '_method'));
        return redirect()->route('reservations.index');
    }

    public function destroy($reservation_id) {
        DB::table('reservations')->where('reservation_id', $reservation_id)->delete();
        return redirect()->route('reservations.index');
    }
}
