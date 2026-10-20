<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // Elegimos el layout dependiendo de quién sea
        $layout = $user->isSuperAdmin() ? 'layouts.superadmin' : 'layouts.app';

        return view('profile.edit', compact('user', 'layout'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            // Si el campo 'password' viene con datos, exigimos la contraseña actual
            'current_password' => $request->filled('password') ? ['required', 'current_password'] : ['nullable'],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
}
