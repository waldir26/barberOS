<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — BarberOS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
    <header class="bg-slate-800 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="font-bold">BarberOS — Super Admin</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm bg-teal-700 hover:bg-teal-800 px-3 py-1.5 rounded">Cerrar sesión</button>
        </form>
    </header>
    <main class="p-6">
        @yield('content')
    </main>
</body>

</html>