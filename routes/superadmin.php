<?php

use App\Http\Controllers\SuperAdmin\BarbershopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn() => view('superadmin.dashboard'))->name('dashboard');

    // CRUD de Barberías
    Route::get('/barbershops', [BarbershopController::class, 'index'])->name('barbershops.index');
    Route::post('/barbershops', [BarbershopController::class, 'store'])->name('barbershops.store');
    Route::match(['post', 'put'], '/barbershops/{barbershop}/update', [BarbershopController::class, 'update'])->name('barbershops.update');
    Route::post('/barbershops/{barbershop}/delete', [BarbershopController::class, 'destroy'])->name('barbershops.destroy');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});
