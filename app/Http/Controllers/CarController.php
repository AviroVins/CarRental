<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    protected $primaryKey = 'plate_number';
    public $incrementing = false;
    protected $keyType = 'string';

    public function index()

    {

        $items = Car::paginate(10);
        $columns = ['plate_number', 'maker', 'model', 'year', 'status'];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'cars',
            'title' => 'Lista samochodów',
        ]);
    }

    public function create()
    {
        $columns = ['plate_number', 'maker', 'model', 'year', 'status'];

        $extraData = [
            'statuses' => ['available', 'rented', 'maintenance'],
        ];

        return view('shared.form', [
            'item' => new Car(),
            'routePrefix' => 'cars',
            'columns' => $columns,
            'title' => 'Dodaj samochód',
            'mode' => 'create',
            'extraData' => $extraData,
        ]);
    }

    public function edit(Car $car)
    {
        $columns = ['plate_number', 'maker', 'model', 'year', 'status'];

        $extraData = [
            'statuses' => ['available', 'rented', 'maintenance'],
        ];

        return view('shared.form', [
            'item' => $car,
            'routePrefix' => 'cars',
            'columns' => $columns,
            'title' => 'Edytuj samochód',
            'mode' => 'edit',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request) {
        DB::table('cars')->insert($request->except('_token'));
        return redirect()->route('cars.index');
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
