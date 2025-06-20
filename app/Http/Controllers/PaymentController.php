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

    public function index()
    {
        $items = Payment::paginate(10);
        $columns = ['payment_id', 'rental_id', 'pesel', 'amount', 'status', 'method'];
        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'payments',
            'title' => 'Lista płatności',
        ]);
    }

    public function create()
    {
        $columns = ['rental_id', 'pesel', 'amount', 'status', 'method'];

        $extraData = [
            'rentals' => DB::table('rentals')->pluck('rental_id', 'rental_id')->toArray(),
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
            'rentals' => DB::table('rentals')->pluck('rental_id', 'rental_id')->toArray(),
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
