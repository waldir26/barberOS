<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ auth()->user()->barbershop->nombre ?? 'BarberOS' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logos/favicon.png') }}">

    <!-- 1. Flatpickr CDN (Se carga primero) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- 2. Tu CSS y JS de Vite (Se carga al final para sobrescribir) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-barber-light text-slate-800 dark:bg-barber-dark dark:text-slate-300 font-sans antialiased flex transition-colors duration-300 overflow-x-hidden">

    <!-- Fondo oscuro para móviles -->
    <div x-show="sidebarOpen" style="display: none;" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm md:hidden" x-on:click="sidebarOpen = false"></div>

    <!-- Menú Lateral Izquierdo -->
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'" class="w-64 bg-white dark:bg-barber-dark border-r border-slate-200 dark:border-slate-700/50 flex flex-col fixed h-full z-40 transition-transform duration-500 ease-in-out md:translate-x-0">

        <!-- ENCABEZADO DEL MENÚ (Logo grande y centrado) -->
        <div class="h-20 flex items-center justify-center px-4 border-b border-slate-200 dark:border-slate-700/50 relative">
            <a href="{{ route('app.dashboard') }}" class="flex items-center justify-center w-full h-full py-2">
                @if(auth()->user()->barbershop && auth()->user()->barbershop->logo)
                <img src="{{ asset('storage/' . auth()->user()->barbershop->logo) }}" alt="Logo" class="max-h-16 w-auto object-contain">
                @else
                <span class="text-xl font-extrabold text-slate-900 dark:text-white truncate" title="{{ auth()->user()->barbershop->nombre ?? 'Barbería' }}">
                    {{ auth()->user()->barbershop->nombre ?? 'Mi Barbería' }}
                </span>
                @endif
            </a>

            <!-- Botón X con efecto notorio al presionar -->
            <button x-on:click="sidebarOpen = false" class="md:hidden absolute right-3 top-1/2 -translate-y-1/2 p-2 text-slate-500 bg-transparent rounded-xl transition-all duration-300 active:scale-75 active:bg-red-100 active:text-red-600 dark:active:bg-red-900/30 dark:active:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800 shadow-sm">
                <i data-lucide="x" class="w-6 h-6 pointer-events-none"></i>
            </button>
        </div>

        <nav x-data="{ 
                scrollPos: sessionStorage.getItem('sidebar-scroll') || 0 
            }"
            x-init="$nextTick(() => { $el.scrollTop = scrollPos; })"
            @scroll.debounce.100ms="sessionStorage.setItem('sidebar-scroll', $el.scrollTop)"
            class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">

            <div class="pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Operación</p>
            </div>
            <a href="{{ route('app.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.dashboard') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('app.dashboard') ? 'text-barber-accent' : '' }}"></i>
                Dashboard
            </a>
            <a href="{{ route('app.appointments.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.appointments.create') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="calendar-plus" class="w-5 h-5 {{ request()->routeIs('app.appointments.create') ? 'text-barber-accent' : '' }}"></i>
                Agendar Cita
            </a>
            <a href="{{ route('app.appointments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.appointments.index') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="list" class="w-5 h-5 {{ request()->routeIs('app.appointments.index') ? 'text-barber-accent' : '' }}"></i>
                Citas
            </a>
            <!-- Calendario -->
            <a href="{{ route('app.appointments.calendar') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.appointments.calendar') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="calendar-days" class="w-5 h-5 {{ request()->routeIs('app.appointments.calendar') ? 'text-barber-accent' : '' }}"></i>
                Calendario
            </a>
            <a href="{{ route('app.clientes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.clientes.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="users" class="w-5 h-5 {{ request()->routeIs('app.clientes.*') ? 'text-barber-accent' : '' }}"></i>
                Clientes
            </a>
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Administración</p>
            </div>
            <a href="{{ route('app.servicios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.servicios.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="scissors" class="w-5 h-5 {{ request()->routeIs('app.servicios.*') ? 'text-barber-accent' : '' }}"></i>
                Servicios
            </a>
            <a href="{{ route('app.barberos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.barberos.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="user-square" class="w-5 h-5 {{ request()->routeIs('app.barberos.*') ? 'text-barber-accent' : '' }}"></i>
                Barberos
            </a>
            <a href="{{ route('app.productos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.productos.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="package" class="w-5 h-5 {{ request()->routeIs('app.productos.*') ? 'text-barber-accent' : '' }}"></i>
                Inventario
            </a>
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Finanzas</p>
            </div>
            <a href="{{ route('app.cash.ventas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.cash.ventas') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="shopping-bag" class="w-5 h-5 {{ request()->routeIs('app.cash.ventas') ? 'text-barber-accent' : '' }}"></i>
                Ventas de Productos
            </a>
            <a href="{{ route('app.cash.gastos') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.cash.gastos') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="receipt" class="w-5 h-5 {{ request()->routeIs('app.cash.gastos') ? 'text-barber-accent' : '' }}"></i>
                Gastos
            </a>
            <a href="{{ route('app.cash.pagos') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.cash.pagos') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="user-check" class="w-5 h-5 {{ request()->routeIs('app.cash.pagos') ? 'text-barber-accent' : '' }}"></i>
                Pagos a Barberos
            </a>
            <a href="{{ route('app.cash.cierre') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.cash.cierre') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="bar-chart-2" class="w-5 h-5 {{ request()->routeIs('app.cash.cierre') ? 'text-barber-accent' : '' }}"></i>
                Cierre de Caja
            </a>
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sistema</p>
            </div>
            <a href="{{ route('app.settings.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.settings.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="settings" class="w-5 h-5 {{ request()->routeIs('app.settings.*') ? 'text-barber-accent' : '' }}"></i>
                Configuración
            </a>
            <a href="{{ route('app.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('app.profile.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:text-slate-900 dark:hover:text-white' }}">
                <i data-lucide="user" class="w-5 h-5 {{ request()->routeIs('app.profile.*') ? 'text-barber-accent' : '' }}"></i>
                Mi Perfil
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200 dark:border-slate-700/50">
            <form method="POST" action="{{ route('logout') }}" x-data @submit="sessionStorage.removeItem('sidebar-scroll')">
                @csrf
                <button class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen w-full">
        <header class="h-16 bg-white/80 dark:bg-barber-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700/50 flex items-center justify-between px-4 md:px-6 sticky top-0 z-20 transition-colors duration-300">
            <div class="flex items-center gap-3">

                <!-- Botón Hamburguesa con efecto táctil al presionar -->
                <button x-on:click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white rounded-lg transition-all active:scale-90">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <h1 class="text-sm font-medium text-slate-500 dark:text-slate-400 hidden sm:block">Panel de la Barbería</h1>
            </div>

            <div class="flex items-center gap-3 md:gap-5">
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

                <button id="theme-toggle" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                </button>

                <a href="{{ route('app.profile.edit') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity border-l border-slate-200 dark:border-slate-700/50 pl-3 md:pl-5" title="Mi Perfil">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 hidden sm:block">{{ auth()->user()->name }}</span>
                    <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-white font-bold border border-slate-300 dark:border-slate-600 text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </a>
            </div>
        </header>

        <main class="p-4 md:p-6 flex-1 w-full overflow-x-hidden">
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
        class="fixed top-20 right-4 md:right-6 z-50">
        <div x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;"
            class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border bg-white dark:bg-barber-card-dark min-w-[280px] md:min-w-[300px]"
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