<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index() {
        $reservations = DB::table('reservations')->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create() {
        $users = DB::table('users')->get();
        $cars = DB::table('cars')->get();
        return view('reservations.create', compact('users', 'cars'));
    }

    public function store(Request $request) {
        DB::table('reservations')->insert($request->except('_token'));
        return redirect()->route('reservations.index');
    }

    public function edit($reservation_id) {
        $reservation = DB::table('reservations')->where('reservation_id', $reservation_id)->first();
        $users = DB::table('users')->get();
        $cars = DB::table('cars')->get();
        return view('reservations.edit', compact('reservation', 'users', 'cars'));
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
