<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::all(); // lub np. Car::where('available', true)->get();
        return view('welcome', compact('cars'));
    }
}