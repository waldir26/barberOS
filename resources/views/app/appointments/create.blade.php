@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Agendar Nueva Cita</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Selecciona el cliente, el servicio y el barbero.</p>
    </div>

    <!-- AVISO PARA CLIENTES NUEVOS -->
    <div class="mb-6 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 flex items-start gap-3 transition-colors duration-300">
        <div class="p-1.5 bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg shrink-0 mt-0.5">
            <i data-lucide="info" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">
                ¿El cliente es nuevo?
            </p>
            <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">
                Si no aparece en la lista, primero debes registrarlo en la sección de
                <a href="{{ route('app.clientes.index') }}" class="font-bold underline hover:text-blue-800 dark:hover:text-white transition-colors">Clientes</a>
                para poder agendarle una cita.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-6">
        <form action="{{ route('app.appointments.store') }}" method="POST" autocomplete="off"
            x-data="{
                search: '',
                selectedClient: null,
                clients: {{ $clients->toJson() }},
                get filteredClients() {
                    if (this.search === '') return [];
                    let query = this.search.toLowerCase();
                    return this.clients.filter(c => 
                        c.nombre.toLowerCase().includes(query) || 
                        (c.telefono && c.telefono.toLowerCase().includes(query))
                    ).slice(0, 5); // Mostrar solo los primeros 5 resultados
                }
            }">
            @csrf

            @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Buscador de Cliente -->
                <div class="md:col-span-2 relative">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cliente *</label>

                    <!-- Input oculto que se envía al servidor -->
                    <input type="hidden" name="client_id" :value="selectedClient ? selectedClient.id : ''">

                    <!-- Si ya hay un cliente seleccionado, mostramos su tarjeta -->
                    <template x-if="selectedClient">
                        <div class="flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 p-3 rounded-lg border border-barber-accent">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white" x-text="selectedClient.nombre"></p>
                                <p class="text-xs text-slate-500" x-text="selectedClient.telefono || 'Sin teléfono'"></p>
                            </div>
                            <button type="button" x-on:click="selectedClient = null; search = ''" class="text-slate-400 hover:text-red-500">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </template>

                    <!-- Si no hay cliente, mostramos el buscador -->
                    <template x-if="!selectedClient">
                        <div class="relative">
                            <input type="text" x-model="search" placeholder="Escribe el nombre o teléfono del cliente..." class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">

                            <!-- Lista de resultados flotante -->
                            <div x-show="search.length > 0" class="absolute z-10 w-full mt-1 bg-white dark:bg-barber-card-dark border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="client in filteredClients" :key="client.id">
                                    <div x-on:click="selectedClient = client; search = ''" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer border-b border-slate-100 dark:border-slate-700/50 last:border-0">
                                        <p class="font-medium text-slate-900 dark:text-white text-sm" x-text="client.nombre"></p>
                                        <p class="text-xs text-slate-500" x-text="client.telefono"></p>
                                    </div>
                                </template>
                                <div x-show="filteredClients.length === 0" class="p-3 text-sm text-slate-500 text-center">
                                    No se encontraron clientes.
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Barbero -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Barbero *</label>
                    <select name="barber_id" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                        <option value="">Seleccione</option>
                        @foreach($barbers as $barber)
                        <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>{{ $barber->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Servicio -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Servicio *</label>
                    <select name="service_id" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                        <option value="">Seleccione</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->nombre }} (${{ $service->precio }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fecha *</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                </div>

                <!-- Hora -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Hora *</label>
                    <input type="time" name="hora" value="{{ old('hora') }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                </div>

                <!-- Notas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notas (Opcional)</label>
                    <input type="text" name="notas" value="{{ old('notas') }}" placeholder="Ej. Llegará 10 min tarde..." class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('app.appointments.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">Cancelar</a>
                <button type="submit" class="bg-barber-accent hover:bg-blue-600 text-white font-medium py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                    Agendar Cita
                </button>
            </div>
        </form>
    </div>
</div>
@endsection