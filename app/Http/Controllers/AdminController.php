<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $tables = ['users', 'cars', 'reservations', 'rentals', 'payments'];

        return view('admin.dashboard', compact('tables'));
    }

    public function showTable(string $name): View
    {
        try {
            $data = DB::table($name)->get();
        } catch (\Exception $e) {
            abort(404, 'Tabela nie istnieje.');
        }

        return view('admin.table', compact('name', 'data'));
    }

    
}
