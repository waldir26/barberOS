<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\SuperAdmin\BarbershopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\App\BarbershopSettingsController;
use App\Http\Controllers\App\ServiceController;
use App\Http\Controllers\App\ClientController;
use App\Http\Controllers\App\AppointmentController;
use App\Http\Controllers\App\BarberController;
use App\Http\Controllers\App\DashboardController;
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

    // CRUD de Barberías
    Route::resource('barbershops', BarbershopController::class)->except(['show']);

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});

// Panel de Barbería
Route::middleware(['auth', 'barbershop'])->prefix('app')->name('app.')->group(function () {

    // Dashboard (Ruta actualizada para usar el controlador)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Configuración de la Barbería
    Route::get('/configuracion', [BarbershopSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/configuracion', [BarbershopSettingsController::class, 'update'])->name('settings.update');

    // Eliminar logo
    Route::delete('/configuracion/logo', [BarbershopSettingsController::class, 'deleteLogo'])->name('settings.delete-logo');

    // Servicios
    Route::resource('servicios', ServiceController::class)->except(['create', 'show', 'edit']);

    // Barberos
    Route::resource('barberos', BarberController::class)->except(['create', 'show', 'edit']);

    // Clientes
    Route::resource('clientes', ClientController::class)->except(['create', 'show', 'edit']);

    // Citas
    Route::get('/citas', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/citas', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/citas/{appointment}/estado', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::delete('/citas/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});
