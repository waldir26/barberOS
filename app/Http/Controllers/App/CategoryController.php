<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categories,nombre'],
        ]);

        Category::create([
            'barbershop_id' => $request->user()->barbershop_id,
            'nombre' => $validated['nombre'],
        ]);

        return redirect()->back()->with('success', 'Categoría creada exitosamente.');
    }

    public function destroy(Category $category)
    {
        // Verificar si tiene productos asociados antes de eliminar
        if ($category->products()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'No puedes eliminar una categoría que tiene productos asignados.']);
        }

        $category->delete();

        return redirect()->back()->with('success', 'Categoría eliminada exitosamente.');
    }
}
