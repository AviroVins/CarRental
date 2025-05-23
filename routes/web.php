<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Kernel;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/table/{name}', [AdminController::class, 'showTable'])->name('admin.table');

    Route::get('/admin/table/{name}/create', [AdminController::class, 'create'])->name('admin.table.create');
    Route::post('/admin/table/{name}', [AdminController::class, 'store'])->name('admin.table.store');

    Route::get('/admin/table/{name}/{id}/edit', [AdminController::class, 'edit'])->name('admin.table.edit');
    Route::put('/admin/table/{name}/{id}', [AdminController::class, 'update'])->name('admin.table.update');

    Route::delete('/admin/table/{name}/{id}', [AdminController::class, 'destroy'])->name('admin.table.delete');
});

require __DIR__.'/auth.php';
