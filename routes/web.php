<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Models\Car;


Route::get('/', function () {
    $cars = Car::all();
    return view('welcome', compact('cars'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Użytkownicy
    Route::resource('users', UserController::class)->parameters(['users' => 'id']);

    // Rezerwacje
    Route::resource('reservations', ReservationController::class)->parameters(['reservations' => 'reservation_id']);

    // Wypożyczenia
    Route::resource('rentals', RentalController::class)->parameters(['rentals' => 'rental_id']);

    // Płatności
    Route::resource('payments', PaymentController::class)->parameters(['payments' => 'payment_id']);

    // Samochody
    Route::get('cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('cars/{plate_number}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('cars/{plate_number}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('cars/{plate_number}', [CarController::class, 'destroy'])->name('cars.destroy');
});

require __DIR__.'/auth.php';
