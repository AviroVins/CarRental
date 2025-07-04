<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }

        if ($request->filled('pesel')) {
            $query->where('pesel', $request->input('pesel'));
        }

        $items = $query->paginate(10)->appends($request->query());

        $columns = ['payment_id', 'rental_id', 'pesel', 'amount', 'status', 'method'];

        $extraData = [
            'statuses' => ['pending', 'paid'],
            'methods' => ['card', 'blik'],
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
        ];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'payments',
            'title' => 'Lista płatności',
            'filters' => $request->only(['status', 'method', 'pesel']),
            'extraData' => $extraData,
        ]);
    }

    public function create()
    {
        $columns = ['rental_id', 'pesel', 'amount', 'status', 'method'];

        $extraData = [
            'rentals' => DB::table('rentals')
                ->join('reservations', 'rentals.reservation_id', '=', 'reservations.reservation_id')
                ->join('cars', 'reservations.plate_number', '=', 'cars.plate_number')
                ->join('users', 'reservations.pesel', '=', 'users.pesel')
                ->select(
                    'rentals.rental_id',
                    DB::raw("CONCAT('ID: ', rentals.rental_id, ' | Rezerwacja: ', rentals.reservation_id, ' | Samochód: ', reservations.plate_number, ' | Rezerwujący: ', users.first_name, ' ',users.last_name) AS label")
                )
                ->pluck('label', 'rental_id')
                ->toArray(),
            'statuses' => ['pending', 'paid'],
            'methods' => ['card', 'blik'],
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
        ];

        return view('shared.form', [
            'item' => new Payment(),
            'routePrefix' => 'payments',
            'columns' => $columns,
            'title' => 'Dodaj płatność',
            'mode' => 'create',
            'extraData' => $extraData,
        ]);
    }

    public function edit(Payment $payment)
    {
        $columns = ['rental_id', 'pesel', 'amount', 'status', 'method'];

        $extraData = [
            'rentals' => DB::table('rentals')
                ->join('reservations', 'rentals.reservation_id', '=', 'reservations.reservation_id')
                ->join('cars', 'reservations.plate_number', '=', 'cars.plate_number')
                ->join('users', 'reservations.pesel', '=', 'users.pesel')
                ->select(
                    'rentals.rental_id',
                    DB::raw("CONCAT('ID: ', rentals.rental_id, ' | Rezerwacja: ', rentals.reservation_id, ' | Samochód: ', reservations.plate_number, ' | Rezerwujący: ', users.first_name, ' ',users.last_name) AS label")
                )
                ->pluck('label', 'rental_id')
                ->toArray(),
            'statuses' => ['pending', 'paid'],
            'methods' => ['card', 'blik'],
            'users' => DB::table('users')
                ->select('pesel', DB::raw("CONCAT(first_name, ' ', last_name, ' (', pesel, ')') AS label"))
                ->pluck('label', 'pesel')
                ->toArray(),
        ];

        return view('shared.form', [
            'item' => $payment,
            'routePrefix' => 'payments',
            'columns' => $columns,
            'title' => 'Edytuj płatność',
            'mode' => 'edit',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'rental_id' => ['required', 'exists:rentals,rental_id'],
            'pesel' => ['required', 'exists:users,pesel'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid'],
            'method' => ['required', 'in:card,blik'],
        ]);

        DB::table('payments')->insert($validated);

        return redirect()->route('payments.index');
    }

    public function update(Request $request, $payment_id) {

        $validated = $request->validate([
            'rental_id' => ['required', 'exists:rentals,rental_id'],
            'pesel' => ['required', 'exists:users,pesel'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid'],
            'method' => ['required', 'in:card,blik'],
        ]);

        DB::table('payments')->where('payment_id', $payment_id)->update($validated);

        return redirect()->route('payments.index');
    }


    public function destroy($payment_id) {
        DB::table('payments')->where('payment_id', $payment_id)->delete();
        return redirect()->route('payments.index');
    }
}
