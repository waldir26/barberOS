<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Appointment;
use Illuminate\Http\Request;
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

        return view('app.cash.ventas', compact('ventas', 'totalVentas'));
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

        return view('app.cash.pagos', compact('pagos', 'totalPagos'));
    }

    // 4. Vista de Cierre de Caja (Resumen General)
    public function indexCierre()
    {
        $hoy = Carbon::today();

        // Suma de servicios cobrados
        $totalServicios = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'ingreso_servicio')
            ->sum('monto');

        // Suma de ventas de productos
        $totalVentas = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'ingreso_producto')
            ->sum('monto');

        // Suma de gastos
        $totalGastos = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'gasto')
            ->sum('monto');

        // Suma de pagos a barberos
        $totalPagos = Transaction::whereDate('created_at', $hoy)
            ->where('tipo', 'pago_barbero')
            ->sum('monto');

        // Total de ingresos y egresos
        $ingresosTotales = $totalServicios + $totalVentas;
        $egresosTotales = $totalGastos + $totalPagos;

        // Balance final (lo que debería haber en caja)
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

    // Acción para guardar una Venta de Producto
    public function storeIngreso(Request $request)
    {
        $validated = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0.01'],
        ]);

        Transaction::create([
            'tipo' => 'ingreso_producto',
            'descripcion' => $validated['descripcion'],
            'monto' => $validated['monto'],
        ]);

        return redirect()->route('app.cash.ventas')->with('success', 'Venta registrada exitosamente.');
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
