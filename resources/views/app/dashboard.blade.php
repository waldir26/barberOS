@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Cargamos el JS exclusivo de este Dashboard -->
@vite(['resources/js/dashboard.js'])

<div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-8 transition-colors duration-300">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
        @php
        $hora = now()->hour;
        if ($hora < 12) { $saludo='Buenos días' ; }
            elseif ($hora < 18) { $saludo='Buenas tardes' ; }
            else { $saludo='Buenas noches' ; }
            @endphp
            {{ $saludo }}, {{ auth()->user()->name }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2">
                Bienvenido al panel de administración de <strong>{{ auth()->user()->barbershop->nombre }}</strong>.
            </p>

            <!-- Aviso si no tiene logo -->
            @if(!auth()->user()->barbershop->logo)
            <div class="mt-6 mb-8 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-colors duration-300">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-xl shrink-0">
                        <i data-lucide="image" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100">¡Personaliza tu barbería!</h3>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Aún no has subido el logo de tu negocio.</p>
                    </div>
                </div>
                <a href="{{ route('app.settings.edit') }}" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl transition-all shadow-sm flex items-center gap-2 text-sm">
                    <i data-lucide="upload" class="w-4 h-4"></i> Subir Logo
                </a>
            </div>
            @endif

            <!-- Tarjetas de resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 {{ auth()->user()->barbershop->logo ? 'mt-8' : 'mt-2' }}">
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-medium text-slate-700 dark:text-slate-300">Citas de Hoy</h3>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $citasHoy }}</p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-lg">
                            <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-medium text-slate-700 dark:text-slate-300">Ingresos del Día</h3>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white">${{ number_format($ingresosHoy, 2) }}</p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-lg">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-medium text-slate-700 dark:text-slate-300">Barberos Activos</h3>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $barberosActivos }}</p>
                </div>
            </div>

            <!-- Sección de Gráficos y Top -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

                <!-- Gráfico de Ingresos -->
                <div class="lg:col-span-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Ingresos de los últimos 7 días</h3>
                    <div class="w-full h-64">
                        <canvas id="ingresosChart"
                            data-fechas="{{ json_encode($fechasSemana) }}"
                            data-ingresos="{{ json_encode($ingresosSemana) }}">
                        </canvas>
                    </div>
                </div>

                <!-- Top Barberos -->
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Barberos Destacados</h3>
                    <div class="space-y-4">
                        @forelse($topBarberos as $index => $top)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-sm font-bold text-slate-500 dark:text-slate-400">
                                    {{ $index + 1 }}
                                </div>
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $top->barber->nombre }}</span>
                            </div>
                            <span class="text-sm font-bold text-barber-accent">{{ $top->total }} citas</span>
                        </div>
                        @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">No hay datos de esta semana.</p>
                        @endforelse
                    </div>
                </div>
            </div>
</div>
@endsection