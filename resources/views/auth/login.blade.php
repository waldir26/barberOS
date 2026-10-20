<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — BarberOS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 dark:bg-barber-dark flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo y Título -->
        <div class="flex flex-col items-center mb-8">
            <img src="{{ asset('logos/logo-claro.png') }}" alt="BarberOS" class="h-20 w-auto block dark:hidden mb-2">
            <img src="{{ asset('logos/logo-oscuro.png') }}" alt="BarberOS" class="h-20 w-auto hidden dark:block mb-2">
            <p class="text-slate-500 dark:text-slate-400">Sistema de gestión de barberías</p>
        </div>

        <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-xl p-8 border border-slate-200 dark:border-slate-700/50">
            @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Usuario</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-barber-accent">
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contraseña</label>
                    <div class="relative">
                        <!-- x-bind:type cambia el tipo de input según la variable -->
                        <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required
                            class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-barber-accent">

                        <!-- x-on:click alterna el estado -->
                        <button type="button" x-on:click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Mostrar u ocultar contraseña">
                            <!-- Ícono del ojo abierto -->
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Ícono del ojo cerrado -->
                            <svg x-show="showPassword" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 dark:border-slate-600 text-barber-accent focus:ring-barber-accent">
                    Recordarme
                </label>

                <button type="submit"
                    class="w-full bg-barber-accent hover:bg-blue-600 text-white font-semibold rounded-lg py-2.5 transition-colors duration-200">
                    Iniciar sesión
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">© {{ date('Y') }} BarberOS</p>
    </div>
</body>

</html>