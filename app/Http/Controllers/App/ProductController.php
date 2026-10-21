<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('app.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'unique:products,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ]);

        $data = [
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'marca' => $validated['marca'],
            'precio' => $validated['precio'],
            'stock' => $validated['stock'],
            'stock_minimo' => $validated['stock_minimo'],
            'activo' => true,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('products', 'public');
            $data['foto'] = $path;
        }

        Product::create($data);

        return redirect()->back()->with('success', 'Producto registrado exitosamente.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'unique:products,codigo,' . $product->id],
            'nombre' => ['required', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ]);

        $product->codigo = $validated['codigo'];
        $product->nombre = $validated['nombre'];
        $product->marca = $validated['marca'];
        $product->precio = $validated['precio'];
        $product->stock = $validated['stock'];
        $product->stock_minimo = $validated['stock_minimo'];

        if ($request->hasFile('foto')) {
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $path = $request->file('foto')->store('products', 'public');
            $product->foto = $path;
        }

        $product->save();

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Producto eliminado.');
    }
}
