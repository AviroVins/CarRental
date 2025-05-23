<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index() {
        $payments = DB::table('payments')->get();
        return view('payments.index', compact('payments'));
    }

    public function create() {
        $rentals = DB::table('rentals')->get();
        return view('payments.create', compact('rentals'));
    }

    public function store(Request $request) {
        DB::table('payments')->insert($request->except('_token'));
        return redirect()->route('payments.index');
    }

    public function edit($payment_id) {
        $payment = DB::table('payments')->where('payment_id', $payment_id)->first();
        $rentals = DB::table('rentals')->get();
        return view('payments.edit', compact('payment', 'rentals'));
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
