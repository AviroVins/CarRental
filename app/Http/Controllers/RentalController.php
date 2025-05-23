<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index() {
        $rentals = DB::table('rentals')->get();
        return view('rentals.index', compact('rentals'));
    }

    public function create() {
        $reservations = DB::table('reservations')->get();
        return view('rentals.create', compact('reservations'));
    }

    public function store(Request $request) {
        DB::table('rentals')->insert($request->except('_token'));
        return redirect()->route('rentals.index');
    }

    public function edit($rental_id) {
        $rental = DB::table('rentals')->where('rental_id', $rental_id)->first();
        $reservations = DB::table('reservations')->get();
        return view('rentals.edit', compact('rental', 'reservations'));
    }

    public function update(Request $request, $rental_id) {
        DB::table('rentals')->where('rental_id', $rental_id)->update($request->except('_token', '_method'));
        return redirect()->route('rentals.index');
    }

    public function destroy($rental_id) {
        DB::table('rentals')->where('rental_id', $rental_id)->delete();
        return redirect()->route('rentals.index');
    }
}
