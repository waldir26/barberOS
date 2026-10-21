@extends('layouts.app')

@section('title', 'Cierre de Caja')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Cierre de Caja</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Resumen general de todas las operaciones del día.</p>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <!-- Ingresos -->
        <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-6">
            <div class="flex items-center gap-3 mb-4 border-b border-slate-100 dark:border-slate-700/50 pb-4">
                <div class="p-2 bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-lg">
                    <i data-lucide="arrow-down-circle" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ingresos</h3>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400 font-medium">Servicios Dados:</span>
                    <span class="font-bold text-slate-900 dark:text-white">${{ number_format($totalServicios, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400 font-medium">Ventas de Productos:</span>
                    <span class="font-bold text-slate-900 dark:text-white">${{ number_format($totalVentas, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <span class="font-semibold text-slate-800 dark:text-slate-200">Total Ingresos:</span>
                    <span class="text-xl font-bold text-green-600 dark:text-green-400">${{ number_format($ingresosTotales, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Egresos -->
        <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-6">
            <div class="flex items-center gap-3 mb-4 border-b border-slate-100 dark:border-slate-700/50 pb-4">
                <div class="p-2 bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-lg">
                    <i data-lucide="arrow-up-circle" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Egresos</h3>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400 font-medium">Gastos de la Barbería:</span>
                    <span class="font-bold text-slate-900 dark:text-white">${{ number_format($totalGastos, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400 font-medium">Pagos a Barberos:</span>
                    <span class="font-bold text-slate-900 dark:text-white">${{ number_format($totalPagos, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <span class="font-semibold text-slate-800 dark:text-slate-200">Total Egresos:</span>
                    <span class="text-xl font-bold text-red-600 dark:text-red-400">${{ number_format($egresosTotales, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Final -->
    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 p-8 text-center shadow-sm">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Balance Final del Día</p>
        <p class="text-5xl font-extrabold {{ $balanceFinal >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            ${{ number_format($balanceFinal, 2) }}
        </p>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">
            Este es el efectivo total que debería haber en la caja.
        </p>
    </div>
</div>
@endsection