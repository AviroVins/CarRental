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
        $userPesel = Auth::user()->pesel;  // załóżmy, że user ma pesel

        // 1. Zarobki z wynajmu na przestrzeni ostatnich 12 miesięcy
        $now = Carbon::now();
        $earningsData = Rental::select(
            DB::raw("TO_CHAR(pickup_time, 'YYYY-MM') as month"),
            DB::raw('SUM(cost) as total_earnings')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->where('pickup_time', '>=', $now->copy()->subMonths(11)->startOfMonth())
        ->pluck('total_earnings', 'month')->toArray();

        // Uzupełnienie miesięcy bez danych zerami
        $months = [];
        $earnings = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $now->copy()->subMonths(11 - $i)->format('Y-m');
            $months[] = $m;
            $earnings[] = $earningsData[$m] ?? 0;
        }

        // 2. Ilość opłaconych i oczekujących płatności
        $paymentsStats = Payment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')->toArray();

        $paidCount = $paymentsStats['paid'] ?? 0;
        $pendingCount = $paymentsStats['pending'] ?? 0;

        // 3. Popularność wynajmu (ilość wypożyczeń) na przestrzeni ostatnich 12 miesięcy
        $rentalsData = Rental::select(
            DB::raw("TO_CHAR(pickup_time, 'YYYY-MM') as month"),
            DB::raw('COUNT(*) as rentals_count')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->where('pickup_time', '>=', $now->copy()->subMonths(11)->startOfMonth())
        ->pluck('rentals_count', 'month')->toArray();

        $popMonths = [];
        $popularity = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $now->copy()->subMonths(11 - $i)->format('Y-m');
            $popMonths[] = $m;
            $popularity[] = $rentalsData[$m] ?? 0;
        }

        return view('finanse', [
            'months' => $months,
            'earnings' => $earnings,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'popMonths' => $popMonths,
            'popularity' => $popularity,
        ]);
    }
}