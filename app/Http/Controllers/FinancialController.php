<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        $userPesel = Auth::user()->pesel;

        $now = Carbon::now();

        // 1. Zarobki z wynajmu – ostatnie 30 dni
        $earningsData = Rental::select(
            DB::raw("TO_CHAR(pickup_time, 'YYYY-MM-DD') as day"),
            DB::raw('SUM(cost) as total_earnings')
        )
        ->where('pickup_time', '>=', $now->copy()->subDays(29)->startOfDay())
        ->groupBy('day')
        ->orderBy('day')
        ->pluck('total_earnings', 'day')->toArray();

        $days = [];
        $earnings = [];
        for ($i = 0; $i < 30; $i++) {
            $d = $now->copy()->subDays(29 - $i)->format('Y-m-d');
            $days[] = $d;
            $earnings[] = $earningsData[$d] ?? 0;
        }

        // 2. Ilość opłaconych i oczekujących płatności
        $paymentsStats = Payment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')->toArray();

        $paidCount = $paymentsStats['paid'] ?? 0;
        $pendingCount = $paymentsStats['pending'] ?? 0;

        // 3. Popularność wynajmu – ostatnie 30 dni
        $rentalsData = Rental::select(
            DB::raw("TO_CHAR(pickup_time, 'YYYY-MM-DD') as day"),
            DB::raw('COUNT(*) as rentals_count')
        )
        ->where('pickup_time', '>=', $now->copy()->subDays(29)->startOfDay())
        ->groupBy('day')
        ->orderBy('day')
        ->pluck('rentals_count', 'day')->toArray();

        $popularity = [];
        foreach ($days as $d) {
            $popularity[] = $rentalsData[$d] ?? 0;
        }

        return view('finanse', [
            'days' => $days,
            'earnings' => $earnings,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'popularity' => $popularity,
        ]);
    }
}
