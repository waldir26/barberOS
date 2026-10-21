<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — BarberOS</title>
    <link rel="icon" type="image/png" href="{{ asset('logos/favicon.png') }}">
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
            @if (str_contains($errors->first(), 'suspendida') || str_contains($errors->first(), 'cancelada'))
            <!-- MENSAJE PERSONALIZADO PARA BARBERÍA SUSPENDIDA -->
            <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-5 py-4 text-sm">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-bold mb-1">Tu barbería está suspendida o cancelada</p>
                        <p class="mb-3">Para reactivar tu acceso, por favor comunícate con nuestro equipo de soporte:</p>
                        <a href="https://wa.me/50375227231?text=Hola,%20mi%20barbería%20está%20suspendida%20y%20necesito%20reactivar%20mi%20acceso%20a%20BarberOS." target="_blank" class="inline-flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1DA851] text-white font-medium py-2 px-4 rounded-lg transition-colors w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                            Hablar con Soporte
                        </a>
                    </div>
                </div>
            </div>
            @else
            <!-- MENSAJE DE ERROR NORMAL -->
            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
            @endif
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
                        <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required
                            class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-barber-accent">

                        <button type="button" x-on:click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Mostrar u ocultar contraseña">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
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