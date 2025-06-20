<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentUserController extends Controller
{
    public function index()
    {
        $userPesel = Auth::user()->pesel;

        $items = Payment::with('rental.car')->where('pesel', $userPesel)->paginate(10);
        $columns = ['payment_id', 'rental_id', 'amount', 'status', 'method'];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => 'user.payments',
            'title' => 'Moje płatności',
        ]);
    }

    public function create()
    {
        $columns = ['rental_id', 'amount', 'status', 'method'];

        $rentals = Rental::pluck('rental_id', 'rental_id');

        $extraData = [
            'rentals' => $rentals,
            'statuses' => ['pending', 'paid'],
            'methods' => ['card', 'blik'],
        ];

        return view('shared.form', [
            'item' => new Payment(),
            'columns' => $columns,
            'routePrefix' => 'user.payments',
            'mode' => 'create',
            'title' => 'Dodaj płatność',
            'extraData' => $extraData,
        ]);
    }

    public function edit(Payment $payment)
    {
        $columns = ['rental_id', 'amount', 'status', 'method'];

        $rentals = Rental::pluck('rental_id', 'rental_id');

        $extraData = [
            'rentals' => $rentals,
            'statuses' => ['pending', 'paid'],
            'methods' => ['card','blik'],
        ];

        return view('shared.form', [
            'item' => $payment,
            'columns' => $columns,
            'routePrefix' => 'user.payments',
            'mode' => 'edit',
            'title' => 'Edytuj płatność',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,rental_id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid',
            'method' => 'required|in:card,blik',
        ]);

        $data = $request->only(['rental_id', 'amount', 'status', 'method']);
        $data['pesel'] = Auth::user()->pesel;

        DB::table('payments')->insert($data);

        return redirect()->route('user.payments.index')->with('success', 'Płatność została dodana.');
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,rental_id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid',
            'method' => 'required|in:card,blik',
        ]);

        $data = $request->only(['rental_id', 'amount', 'status', 'method']);
        $data['pesel'] = Auth::user()->pesel;

        DB::table('payments')->where('payment_id', $payment->payment_id)->update($data);

        return redirect()->route('user.payments.index')->with('success', 'Płatność została zaktualizowana.');
    }

    public function destroy(Payment $payment)
    {
        DB::table('payments')->where('payment_id', $payment->payment_id)->delete();

        return redirect()->route('user.payments.index')->with('success', 'Płatność została usunięta.');
    }

    public function pay(Payment $payment)
    {
        if ($payment->status === 'pending') {
            $payment->update(['status' => 'paid']);
            return redirect()->route('user.payments.index')->with('success', 'Płatność została oznaczona jako opłacona.');
        }

        return redirect()->route('user.payments.index')->with('error', 'Płatność już została opłacona.');
    }

    public function changePayment(Payment $payment)
    {
        if ($payment->status === 'pending') {
            if ($payment->method === 'blik') {
                $payment->update(['method' => 'card']);
                return redirect()->route('user.payments.index')->with('success', 'Metoda płatności została zmieniona na kartę.');
            }
            else if ($payment->method === 'card') {
                $payment->update(['method' => 'blik']);
                return redirect()->route('user.payments.index')->with('success', 'Metoda płatności została zmieniona na BLIK.');
            }
        }

        return redirect()->route('user.payments.index')->with('error', 'Nie udało się zmienić metody płatności.');
    }
}