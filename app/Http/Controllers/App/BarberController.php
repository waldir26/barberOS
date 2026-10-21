<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::latest()->paginate(10);
        return view('app.barbers.index', compact('barbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'activo' => true,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('barbers', 'public');
            $data['foto'] = $path;
        }

        Barber::create($data);

        return redirect()->back()->with('success', 'Barbero creado exitosamente.');
    }

    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ]);

        $barber->nombre = $validated['nombre'];
        $barber->telefono = $validated['telefono'];

        if ($request->hasFile('foto')) {
            // Borramos la foto anterior si existe
            if ($barber->foto && Storage::disk('public')->exists($barber->foto)) {
                Storage::disk('public')->delete($barber->foto);
            }
            $path = $request->file('foto')->store('barbers', 'public');
            $barber->foto = $path;
        }

        $barber->save();

        return redirect()->back()->with('success', 'Barbero actualizado correctamente.');
    }

    public function destroy(Barber $barber)
    {
        if ($barber->foto && Storage::disk('public')->exists($barber->foto)) {
            Storage::disk('public')->delete($barber->foto);
        }

        $barber->delete();

        return redirect()->back()->with('success', 'Barbero eliminado.');
    }
}
