<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — BarberOS</title>
    <link rel="icon" type="image/png" href="{{ asset('logos/favicon.png') }}">
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

        <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
            <!-- Botón Dashboard -->
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('superadmin.dashboard') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('superadmin.dashboard') ? 'text-barber-accent' : '' }}"></i>
                Dashboard
            </a>

            <!-- Botón Barberías -->
            <a href="{{ route('superadmin.barbershops.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('superadmin.barbershops.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="store" class="w-5 h-5 {{ request()->routeIs('superadmin.barbershops.*') ? 'text-barber-accent' : '' }}"></i>
                Barberías
            </a>

            <!-- Separador sutil -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sistema</p>
            </div>

            <!-- Botón Respaldos -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="database" class="w-5 h-5"></i>
                Respaldos
            </a>

            <!-- Botón Auditoría -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
                Auditoría
            </a>

            <!-- Botón Configuración -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Configuración
            </a>

            <!-- Botón Perfil -->
            <a href="{{ route('superadmin.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('superadmin.profile.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="user" class="w-5 h-5 {{ request()->routeIs('superadmin.profile.*') ? 'text-barber-accent' : '' }}"></i>
                Mi Perfil
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

                <!-- Enlace al perfil desde el nombre del usuario -->
                <a href="{{ route('superadmin.profile.edit') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity" title="Mi Perfil">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ auth()->user()->name }}</span>
                    <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-white font-bold border border-slate-300 dark:border-slate-600">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </a>
            </div>
        </header>

        <!-- Área de la Vista -->
        <main class="p-6 flex-1">
            @yield('content')
        </main>
    </div>

    <!-- Contenedor de Notificaciones (Toasts) -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            showToast(msg, t = 'success') {
                this.message = msg;
                this.type = t;
                this.show = true;
                setTimeout(() => { this.show = false; }, 4000); // Se oculta solo a los 4 segundos
            }
        }"
        x-init="
            @if(session('success')) showToast('{{ session('success') }}', 'success'); @endif
            @if(session('error')) showToast('{{ session('error') }}', 'error'); @endif
        "
        class="fixed top-20 right-6 z-50">

        <!-- Tarjeta de Notificación -->
        <div x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;"
            class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border bg-white dark:bg-barber-card-dark min-w-[300px]"
            :class="type === 'success' ? 'border-green-200 dark:border-green-800' : 'border-red-200 dark:border-red-800'">

            <!-- Icono -->
            <div :class="type === 'success' ? 'text-green-500' : 'text-red-500'">
                <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg x-show="type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Mensaje -->
            <div>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200" x-text="message"></p>
            </div>

            <!-- Botón de cerrar -->
            <button @click="show = false" class="ml-auto text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</body>

</html>