<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashController extends Controller
{
    // 1. Vista de Ventas de Productos
    public function indexVentas()
    {
        $hoy = Carbon::today();

        $ventas = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'ingreso_producto')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalVentas = $ventas->sum('monto');

        $products = Product::where('activo', true)
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('app.cash.ventas', compact('ventas', 'totalVentas', 'products'));
    }

    // 2. Vista de Gastos
    public function indexGastos()
    {
        $hoy = Carbon::today();
        $gastos = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'gasto')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalGastos = $gastos->sum('monto');

        return view('app.cash.gastos', compact('gastos', 'totalGastos'));
    }

    // 3. Vista de Pagos a Barberos
    public function indexPagos()
    {
        $hoy = Carbon::today();
        $pagos = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'pago_barbero')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPagos = $pagos->sum('monto');

        // Enviamos la lista de barberos activos
        $barbers = Barber::where('activo', true)->orderBy('nombre')->get();

        return view('app.cash.pagos', compact('pagos', 'totalPagos', 'barbers'));
    }

    // 4. Vista de Cierre de Caja
    public function indexCierre()
    {
        $hoy = Carbon::today();

        $totalServicios = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'ingreso_servicio')
            ->sum('monto');

        $totalVentas = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'ingreso_producto')
            ->sum('monto');

        $totalGastos = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'gasto')
            ->sum('monto');

        $totalPagos = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'pago_barbero')
            ->sum('monto');

        $ingresosTotales = $totalServicios + $totalVentas;
        $egresosTotales = $totalGastos + $totalPagos;
        $balanceFinal = $ingresosTotales - $egresosTotales;

        return view('app.cash.cierre', compact(
            'totalServicios',
            'totalVentas',
            'totalGastos',
            'totalPagos',
            'ingresosTotales',
            'egresosTotales',
            'balanceFinal'
        ));
    }

    // Acción para guardar una Venta de Producto (Carrito)
    public function storeIngreso(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $totalVenta = 0;
        $descripcionVenta = [];

        try {
            DB::beginTransaction();

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['cantidad']) {
                    throw new \Exception("No hay suficiente stock de: " . $product->nombre);
                }

                $totalVenta += ($product->precio * $item['cantidad']);
                $descripcionVenta[] = $product->nombre . ' (x' . $item['cantidad'] . ')';

                // Descontar stock
                $product->decrement('stock', $item['cantidad']);
            }

            // Registrar la venta en caja
            Transaction::create([
                'tipo' => 'ingreso_producto',
                'descripcion' => implode(', ', $descripcionVenta),
                'monto' => $totalVenta,
            ]);

            DB::commit();

            return redirect()->route('app.cash.ventas')->with('success', 'Venta registrada y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Acción para guardar un Gasto o Pago a Barbero
    public function storeGasto(Request $request)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'in:gasto,pago_barbero'],
            'descripcion' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0.01'],
        ]);

        Transaction::create([
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'],
            'monto' => $validated['monto'],
        ]);

        if ($validated['tipo'] === 'gasto') {
            return redirect()->route('app.cash.gastos')->with('success', 'Gasto registrado exitosamente.');
        } else {
            return redirect()->route('app.cash.pagos')->with('success', 'Pago a barbero registrado exitosamente.');
        }
    }

    // Eliminar un movimiento
    public function destroy(Transaction $transaction)
    {
        $tipo = $transaction->tipo;
        $transaction->delete();

        if ($tipo === 'ingreso_producto') {
            return redirect()->route('app.cash.ventas')->with('success', 'Movimiento eliminado.');
        } elseif ($tipo === 'gasto') {
            return redirect()->route('app.cash.gastos')->with('success', 'Movimiento eliminado.');
        } else {
            return redirect()->route('app.cash.pagos')->with('success', 'Movimiento eliminado.');
        }
    }
}
