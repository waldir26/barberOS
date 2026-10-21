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
use App\Http\Controllers\App\CashController;
use App\Http\Controllers\App\ProductController;
use App\Http\Controllers\App\CategoryController;
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

    // CRUD de Barberías (Rutas manuales con POST)
    Route::get('/barbershops', [BarbershopController::class, 'index'])->name('barbershops.index');
    Route::post('/barbershops', [BarbershopController::class, 'store'])->name('barbershops.store');
    Route::post('/barbershops/{barbershop}/update', [BarbershopController::class, 'update'])->name('barbershops.update');
    Route::post('/barbershops/{barbershop}/delete', [BarbershopController::class, 'destroy'])->name('barbershops.destroy');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});

// Panel de Barbería
Route::middleware(['auth', 'barbershop'])->prefix('app')->name('app.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Configuración de la Barbería
    Route::get('/configuracion', [BarbershopSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/configuracion', [BarbershopSettingsController::class, 'update'])->name('settings.update');
    Route::delete('/configuracion/logo', [BarbershopSettingsController::class, 'deleteLogo'])->name('settings.delete-logo');

    // Servicios
    Route::get('/servicios', [ServiceController::class, 'index'])->name('servicios.index');
    Route::post('/servicios', [ServiceController::class, 'store'])->name('servicios.store');
    Route::post('/servicios/{service}/update', [ServiceController::class, 'update'])->name('servicios.update');
    Route::post('/servicios/{service}/delete', [ServiceController::class, 'destroy'])->name('servicios.delete');

    // Barberos
    Route::get('/barberos', [BarberController::class, 'index'])->name('barberos.index');
    Route::post('/barberos', [BarberController::class, 'store'])->name('barberos.store');
    Route::post('/barberos/{barber}/update', [BarberController::class, 'update'])->name('barberos.update');
    Route::post('/barberos/{barber}/delete', [BarberController::class, 'destroy'])->name('barberos.delete');

    // Clientes
    Route::get('/clientes', [ClientController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
    Route::post('/clientes/{client}/update', [ClientController::class, 'update'])->name('clientes.update');
    Route::post('/clientes/{client}/delete', [ClientController::class, 'destroy'])->name('clientes.delete');

    // Inventario (Productos)
    Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductController::class, 'store'])->name('productos.store');
    Route::post('/productos/{product}/update', [ProductController::class, 'update'])->name('productos.update');
    Route::post('/productos/{product}/delete', [ProductController::class, 'destroy'])->name('productos.delete');

    // Categorías de Productos
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categorias.store');
    Route::post('/categorias/{category}/delete', [CategoryController::class, 'destroy'])->name('categorias.destroy');

    // Citas
    Route::get('/citas/agendar', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::get('/citas', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/citas', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/citas/{appointment}/update', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::post('/citas/{appointment}/estado', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::post('/citas/{appointment}/pagar', [AppointmentController::class, 'pay'])->name('appointments.pay');
    Route::post('/citas/{appointment}/delete', [AppointmentController::class, 'destroy'])->name('appointments.delete');

    // Finanzas
    Route::get('/finanzas/ventas', [CashController::class, 'indexVentas'])->name('cash.ventas');
    Route::get('/finanzas/gastos', [CashController::class, 'indexGastos'])->name('cash.gastos');
    Route::get('/finanzas/pagos', [CashController::class, 'indexPagos'])->name('cash.pagos');
    Route::get('/finanzas/cierre', [CashController::class, 'indexCierre'])->name('cash.cierre');

    // Acciones de Caja
    Route::post('/finanzas/ingreso', [CashController::class, 'storeIngreso'])->name('cash.store-ingreso');
    Route::post('/finanzas/gasto', [CashController::class, 'storeGasto'])->name('cash.store-gasto');
    Route::post('/finanzas/{transaction}/delete', [CashController::class, 'destroy'])->name('cash.delete');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});
