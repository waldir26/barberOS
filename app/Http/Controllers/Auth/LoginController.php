<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'username' => 'Usuario o contraseña incorrectos.',
            ]);
        }

        $user = Auth::user();

        if (!$user->activo) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Tu usuario está inactivo. Contacta al administrador.',
            ]);
        }

        if ($user->isBarbershopUser()) {
            $estado = $user->barbershop?->estado;
            if (!in_array($estado, ['activa', 'en_prueba'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Tu barbería está suspendida o cancelada.',
                ]);
            }
        }

        $request->session()->regenerate();
        $user->forceFill(['ultimo_login' => now()])->save();

        if ($user->debe_cambiar_password) {
            return redirect()->route('password.change');
        }

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    protected function redirectByRole()
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        return redirect()->route('app.dashboard');
    }
}
