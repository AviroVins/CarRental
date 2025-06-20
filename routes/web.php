<?php

use App\Http\Controllers\ReservationUserController;
use App\Http\Controllers\PaymentUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Models\Car;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    $cars = Car::all();
    return view('welcome', compact('cars'));
});

Route::get('/welcome', function () {
    $cars = Car::all();
    return view('welcome', compact('cars'));
})->middleware('auth')->name('welcome');



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('cars', CarController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('rentals', RentalController::class);
    Route::resource('payments', PaymentController::class);
});

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::resource('reservations', ReservationUserController::class)
        ->names('user.reservations')
        ->except(['show']);

    Route::patch('reservations/{reservation}/finish', [ReservationUserController::class, 'finish'])->name('user.reservations.finish');
    
    Route::resource('payments', PaymentUserController::class)
        ->names('user.payments')
        ->except(['show']);

    Route::patch('payments/{payment}/pay', [PaymentUserController::class, 'pay'])->name('user.payments.pay');
    Route::patch('payments/{payment}/method', [PaymentUserController::class, 'changePayment'])->name('user.payments.changePayment');

});


require __DIR__.'/auth.php';
