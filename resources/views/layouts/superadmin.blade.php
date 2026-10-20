<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — BarberOS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Lucide icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-barber-light text-slate-800 dark:bg-barber-dark dark:text-slate-300 font-sans antialiased flex transition-colors duration-300">

    <!-- Menú Lateral Izquierdo -->
    <aside class="w-64 bg-white dark:bg-barber-dark border-r border-slate-200 dark:border-slate-700/50 flex flex-col hidden md:flex fixed h-full z-20 transition-colors duration-300">
        <div class="h-16 flex items-center px-6 border-b border-slate-200 dark:border-slate-700/50">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center">
                <!-- Logo Modo Claro -->
                <img src="{{ asset('logos/logo-claro.png') }}" alt="BarberOS" class="h-15 w-auto block dark:hidden">
                <!-- Logo Modo Oscuro -->
                <img src="{{ asset('logos/logo-oscuro.png') }}" alt="BarberOS" class="h-15 w-auto hidden dark:block">
            </a>
        </div>

        <nav class="flex-1 py-6 px-4 space-y-2 overflow-y-auto">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white transition-all font-medium">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-barber-accent"></i>
                Dashboard
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200 dark:border-slate-700/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- Barra Superior -->
        <header class="h-16 bg-white/80 dark:bg-barber-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700/50 flex items-center justify-between px-6 sticky top-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <h1 class="text-sm font-medium text-slate-500 dark:text-slate-400">Panel de Control Global</h1>
            </div>

            <div class="flex items-center gap-4">
                <!-- Botón Modo Claro / Oscuro -->
                <button id="theme-toggle" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                </button>

                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ auth()->user()->name }}</span>
                <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-white font-bold border border-slate-300 dark:border-slate-600">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Área de la Vista -->
        <main class="p-6 flex-1">
            @yield('content')
        </main>
    </div>
</body>

</html>