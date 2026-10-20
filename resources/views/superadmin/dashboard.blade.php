@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-6">
    <h2 class="text-xl font-semibold">Bienvenido, {{ auth()->user()->name }}</h2>
    <p class="text-slate-500 dark:text-slate-400 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
</div>
@endsection