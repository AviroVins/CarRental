<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    public function index() {
        $cars = DB::table('cars')->get();
        return view('cars.index', compact('cars'));
    }

    public function create() {
        return view('cars.create');
    }

    public function store(Request $request) {
        DB::table('cars')->insert($request->except('_token'));
        return redirect()->route('cars.index');
    }

    public function edit($plate_number) {
        $car = DB::table('cars')->where('plate_number', $plate_number)->first();
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, $plate_number) {
        DB::table('cars')->where('plate_number', $plate_number)->update($request->except('_token', '_method'));
        return redirect()->route('cars.index');
    }

    public function destroy($plate_number) {
        DB::table('cars')->where('plate_number', $plate_number)->delete();
        return redirect()->route('cars.index');
    }
}
