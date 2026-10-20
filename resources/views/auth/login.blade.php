<!DOCTYPE html>
<html lang="es" class="{{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — BarberOS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 dark:bg-slate-900 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">BarberOS</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Sistema de gestión de barberías</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 border border-slate-200 dark:border-slate-700">
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
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Mostrar u ocultar contraseña">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 dark:border-slate-600 text-teal-600 focus:ring-teal-500">
                    Recordarme
                </label>

                <button type="submit"
                    class="w-full bg-teal-700 hover:bg-teal-800 text-white font-semibold rounded-lg py-2.5 transition-colors duration-200">
                    Iniciar sesión
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">© {{ date('Y') }} BarberOS</p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>