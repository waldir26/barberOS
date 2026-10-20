<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBarbershopUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->barbershop_id === null) {
            abort(403, 'No tienes permiso para acceder aquí.');
        }

        if ($user->barbershop && !in_array($user->barbershop->estado, ['activa', 'en_prueba'])) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['username' => 'Tu barbería está suspendida o cancelada.']);
        }

        return $next($request);
    }
}
