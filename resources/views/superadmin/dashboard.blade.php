@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-8 transition-colors duration-300">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
        @php
        $hora = now()->hour;
        if ($hora < 12) {
            $saludo='Buenos días' ;
            } elseif ($hora < 18) {
            $saludo='Buenas tardes' ;
            } else {
            $saludo='Buenas noches' ;
            }
            @endphp
            {{ $saludo }}, {{ auth()->user()->name }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2">
                Bienvenido al panel de control global de BarberOS.
            </p>
</div>
@endsection