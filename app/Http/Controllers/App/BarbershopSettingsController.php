<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarbershopSettingsController extends Controller
{
    public function edit()
    {
        $barbershop = Auth::user()->barbershop;
        return view('app.settings.edit', compact('barbershop'));
    }

    public function update(Request $request)
    {
        $barbershop = Auth::user()->barbershop;

        // Mensajes personalizados
        $messages = [
            'logo.max' => 'El logo no debe pesar más de 10 MB.',
            'logo.image' => 'El archivo debe ser una imagen válida.',
            'logo.mimes' => 'El logo debe ser de tipo: jpeg, png, jpg o webp.',
        ];

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:255'],
            'telefono' => ['required', 'numeric', 'digits:8'],
            // 10240 KB = 10MB
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ], $messages);

        $telefonoCompleto = '+503 ' . $validated['telefono'];

        $barbershop->nombre = $validated['nombre'];
        $barbershop->telefono = $telefonoCompleto;

        // Si subieron un nuevo logo
        if ($request->hasFile('logo')) {
            // Borramos el logo anterior si existe
            if ($barbershop->logo && Storage::disk('public')->exists($barbershop->logo)) {
                Storage::disk('public')->delete($barbershop->logo);
            }
            // Guardamos el nuevo
            $path = $request->file('logo')->store('logos', 'public');
            $barbershop->logo = $path;
        }

        $barbershop->save();

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
