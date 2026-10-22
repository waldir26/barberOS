@extends('layouts.app')

@section('title', 'Pagos a Barberos')

@section('content')
<div x-data="{ 
    pagoModal: {{ $errors->any() ? 'true' : 'false' }}, 
    deleteModal: false,
    deleteAction: ''
}">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Pagos a Barberos</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Registro de salidas de dinero por comisiones o salarios.</p>
        </div>

        <button x-on:click="pagoModal = true" class="w-full sm:w-auto bg-barber-accent hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm shrink-0">
            <i data-lucide="user-check" class="w-5 h-5"></i> Registrar Pago
        </button>
    </div>

    <!-- CARTEL DE ERRORES -->
    @if ($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 text-sm flex items-start gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-bold mb-1">No se pudo procesar el pago:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- FILTROS DE BÚSQUEDA (NUEVO) -->
    <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-4 mb-6">
        <form action="{{ route('app.cash.pagos') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">

            <!-- Fecha Inicio -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Fecha Inicio</label>
                <input type="text" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
            </div>

            <!-- Fecha Fin -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Fecha Fin</label>
                <input type="text" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
            </div>

            <!-- Barbero -->
            <div class="w-full md:w-auto flex-1">
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Barbero (Opcional)</label>
                <select name="barber_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                    <option value="">Todos los barberos</option>
                    @foreach($barbers as $barber)
                    <option value="{{ $barber->id }}" {{ $barberId == $barber->id ? 'selected' : '' }}>{{ $barber->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-medium py-2 px-6 rounded-lg transition-all flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> Filtrar
            </button>

            <a href="{{ route('app.cash.pagos') }}" class="w-full md:w-auto text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white py-2 px-4 text-center">Limpiar</a>
        </form>
    </div>

    <!-- Tarjeta de Total -->
    <div class="bg-white dark:bg-barber-card-dark rounded-xl p-6 border border-slate-200 dark:border-slate-700/50 shadow-sm mb-6">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total pagado en el período</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">${{ number_format($totalPagos, 2) }}</p>
    </div>

    <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-sm border-b border-slate-200 dark:border-slate-700/50">
                        <th class="px-6 py-4 font-medium">Fecha / Hora</th>
                        <th class="px-6 py-4 font-medium">Barbero / Concepto</th>
                        <th class="px-6 py-4 font-medium">Monto</th>
                        <th class="px-6 py-4 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                    @forelse ($pagos as $pago)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $pago->created_at->format('d/m/Y') }}</div>
                            <div>{{ $pago->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $pago->descripcion }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                            <!-- SE QUITÓ EL SIGNO NEGATIVO AQUÍ -->
                            ${{ number_format($pago->monto, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form id="delete-form-{{ $pago->id }}" action="{{ route('app.cash.delete', $pago->id) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                            <button type="button" onclick="if(confirm('¿Eliminar este pago?')) document.getElementById('delete-form-{{ $pago->id }}').submit();" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Eliminar">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <i data-lucide="user-check" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600"></i>
                            <p>No hay pagos registrados en este período.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE PAGO -->
    <div x-show="pagoModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="pagoModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="pagoModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-barber-card-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700/50">

                <form action="{{ route('app.cash.store-gasto') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="tipo" value="pago_barbero">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700/50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modal-title">Pago a Barbero</h3>
                        <button type="button" x-on:click="pagoModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Seleccionar Barbero *</label>
                            <select name="descripcion" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                                <option value="" disabled selected hidden>— Seleccione el barbero —</option>
                                @foreach($barbers as $barber)
                                <option value="Pago a {{ $barber->nombre }}" {{ old('descripcion') == 'Pago a '.$barber->nombre ? 'selected' : '' }}>{{ $barber->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- NUEVO CAMPO: CONCEPTO -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Concepto / Nota (Opcional)</label>
                            <input type="text" name="concepto" value="{{ old('concepto') }}" placeholder="Ej. Pago semanal, Comisión 5 cortes..." class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Monto ($) *</label>
                            <input type="number" step="0.01" name="monto" value="{{ old('monto') }}" required min="0.01" placeholder="0.00" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" x-on:click="pagoModal = false" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">Cancelar</button>
                        <button type="submit" class="bg-barber-accent hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-xl transition-all shadow-sm flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Guardar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection