<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{

    protected $primaryKey = 'rental_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public function index()
    {
        $items = Rental::all();
        $columns = ['rental_id', 'reservation_id', 'user_id', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];
        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'rentals',
            'title' => 'Lista wypożyczeń',
        ]);
    }

    public function create()
    {

        $columns = ['rental_id', 'reservation_id', 'user_id', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

        return view('shared.form', [
            'item' => new Rental(),
            'routePrefix' => 'rentals',
            'columns' => $columns,
            'title' => 'Dodaj wypożyczenie',
            'mode' => 'create',
        ]);
    }

    public function edit(Rental $rental)
    {
        $columns = ['rental_id', 'reservation_id', 'user_id', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

        return view('shared.form', [
            'item' => $rental,
            'routePrefix' => 'rentals',
            'columns' => $columns,
            'title' => 'Edytuj wypożyczenie',
            'mode' => 'edit',
        ]);
    }

    public function store(Request $request) {
        DB::table('rentals')->insert($request->except('_token'));
        return redirect()->route('rentals.index');
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
