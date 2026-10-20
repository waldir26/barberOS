<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ auth()->user()->barbershop->nombre ?? 'BarberOS' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logos/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-barber-light text-slate-800 dark:bg-barber-dark dark:text-slate-300 font-sans antialiased flex transition-colors duration-300">

    <!-- Menú Lateral Izquierdo -->
    <aside class="w-64 bg-white dark:bg-barber-dark border-r border-slate-200 dark:border-slate-700/50 flex flex-col hidden md:flex fixed h-full z-20 transition-colors duration-300">
        <div class="h-16 flex items-center px-6 border-b border-slate-200 dark:border-slate-700/50">
            <a href="{{ route('app.dashboard') }}" class="flex items-center">
                @if(auth()->user()->barbershop && auth()->user()->barbershop->logo)
                <!-- Si la barbería ya subió su logo, lo mostramos -->
                <img src="{{ asset('storage/' . auth()->user()->barbershop->logo) }}" alt="Logo" class="h-10 w-auto">
                @else
                <!-- Si no, mostramos el nombre de la barbería -->
                <span class="text-xl font-extrabold text-slate-900 dark:text-white truncate" title="{{ auth()->user()->barbershop->nombre ?? 'Barbería' }}">
                    {{ auth()->user()->barbershop->nombre ?? 'Mi Barbería' }}
                </span>
                @endif
            </a>
        </div>

        <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">

            <div class="pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Operación</p>
            </div>

            <!-- Dashboard -->
            <a href="{{ route('app.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.dashboard') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('app.dashboard') ? 'text-barber-accent' : '' }}"></i>
                Dashboard
            </a>

            <!-- Citas -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                Agenda y Citas
            </a>

            <!-- Clientes -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="users" class="w-5 h-5"></i>
                Clientes
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Administración</p>
            </div>

            <!-- Servicios -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="scissors" class="w-5 h-5"></i>
                Servicios
            </a>

            <!-- Barberos -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="user-square" class="w-5 h-5"></i>
                Barberos
            </a>

            <!-- Inventario -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="package" class="w-5 h-5"></i>
                Inventario
            </a>

            <!-- Caja -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                Caja y Pagos
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sistema</p>
            </div>

            <!-- Configuración -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Configuración
            </a>

            <!-- Mi Perfil -->
            <a href="{{ route('app.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.profile.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="user" class="w-5 h-5 {{ request()->routeIs('app.profile.*') ? 'text-barber-accent' : '' }}"></i>
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
                <h1 class="text-sm font-medium text-slate-500 dark:text-slate-400">Panel de la Barbería</h1>
            </div>

            <div class="flex items-center gap-5">
                <!-- Reloj en tiempo real -->
                <div x-data="{ 
                        time: new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }) 
                    }"
                    x-init="setInterval(() => { 
                        time = new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }) 
                    }, 1000)"
                    class="hidden lg:flex items-center gap-3 text-xs font-medium text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700/50 transition-all duration-300 hover:bg-white dark:hover:bg-slate-800 hover:shadow-md cursor-default">

                    <span class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-barber-accent"></i>
                        {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                    </span>
                    <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                    <span class="flex items-center gap-1.5 uppercase tracking-wide">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-barber-accent"></i>
                        <span x-text="time">--:--:-- --</span>
                    </span>
                </div>

                <!-- Botón Modo Claro / Oscuro -->
                <button id="theme-toggle" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                </button>

                <!-- Perfil de Usuario -->
                <a href="{{ route('app.profile.edit') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity border-l border-slate-200 dark:border-slate-700/50 pl-5" title="Mi Perfil">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 hidden sm:block">{{ auth()->user()->name }}</span>
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
                setTimeout(() => { this.show = false; }, 4000);
            }
        }"
        x-init="
            @if(session('success')) showToast('{{ session('success') }}', 'success'); @endif
            @if(session('error')) showToast('{{ session('error') }}', 'error'); @endif
        "
        class="fixed top-20 right-6 z-50">
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
            <div :class="type === 'success' ? 'text-green-500' : 'text-red-500'">
                <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg x-show="type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200" x-text="message"></p>
            </div>
            <button @click="show = false" class="ml-auto text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</body>

</html>