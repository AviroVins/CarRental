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
        $items = Payment::all();
        $columns = ['payment_id', 'rental_id', 'user_id', 'amount', 'status', 'method'];
        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'payments',
            'title' => 'Lista płatności',
        ]);
    }

    public function create()
    {
        $columns = ['payment_id', 'rental_id', 'user_id', 'amount', 'status', 'method'];

        return view('shared.form', [
            'item' => new Payment(),
            'routePrefix' => 'payments',
            'columns' => $columns,
            'title' => 'Dodaj płatność',
            'mode' => 'create',
        ]);
    }

    public function edit(Payment $payment)
    {
        $columns = ['payment_id', 'rental_id', 'user_id', 'amount', 'status', 'method'];

        return view('shared.form', [
            'item' => $payment,
            'routePrefix' => 'payments',
            'columns' => $columns,
            'title' => 'Edytuj płatność',
            'mode' => 'edit',
        ]);
    }

    public function store(Request $request) {
        DB::table('payments')->insert($request->except('_token'));
        return redirect()->route('payments.index');
    }

    public function update(Request $request, $payment_id) {
        DB::table('payments')->where('payment_id', $payment_id)->update($request->except('_token', '_method'));
        return redirect()->route('payments.index');
    }

    public function destroy($payment_id) {
        DB::table('payments')->where('payment_id', $payment_id)->delete();
        return redirect()->route('payments.index');
    }
}
