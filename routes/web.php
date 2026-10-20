<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordChangeController;
use Illuminate\Support\Facades\Route;

// Redirige la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Muestra el formulario de login (GET /login)
Route::get('/login', [LoginController::class, 'show'])->name('login');

// Procesa el login (POST /login)
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Cierra sesión (POST /logout)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas solo para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Muestra el formulario de cambio de contraseña obligatorio
    Route::get('/cambiar-password', [PasswordChangeController::class, 'show'])->name('password.change');

    // Procesa el cambio de contraseña
    Route::post('/cambiar-password', [PasswordChangeController::class, 'update'])->name('password.change.post');
});

// Panel Super Admin
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn() => view('superadmin.dashboard'))->name('dashboard');
});

// Panel de Barbería
Route::middleware(['auth', 'barbershop'])->prefix('app')->name('app.')->group(function () {
    Route::get('/dashboard', fn() => view('app.dashboard'))->name('dashboard');
});
