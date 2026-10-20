@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Configuración de la Barbería</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Actualiza los datos públicos de tu negocio.</p>
    </div>

    <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-6">
        <form action="{{ route('app.settings.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="space-y-6">
                <!-- Logo (Con previsualización en tiempo real) -->
                <div x-data="{ 
                        logoPreview: null 
                    }">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Logo de la Barbería</label>
                    <div class="flex items-center gap-6">

                        <!-- Contenedor de Previsualización -->
                        <div class="w-24 h-24 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                            <!-- Si se selecciona un archivo nuevo, mostramos la previsualización -->
                            <template x-if="logoPreview">
                                <img :src="logoPreview" class="w-full h-full object-contain p-2">
                            </template>

                            <!-- Si no hay previsualización, mostramos el logo guardado o el ícono -->
                            <template x-if="!logoPreview">
                                <div class="w-full h-full flex items-center justify-center">
                                    @if($barbershop->logo)
                                    <img src="{{ asset('storage/' . $barbershop->logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                                    @else
                                    <i data-lucide="image" class="w-8 h-8 text-slate-400"></i>
                                    @endif
                                </div>
                            </template>
                        </div>

                        <div>
                            <!-- Al cambiar el input, actualizamos la variable logoPreview -->
                            <input type="file" name="logo" accept="image/*"
                                x-on:change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { logoPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    }
                                "
                                class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-barber-accent/10 file:text-barber-accent hover:file:bg-barber-accent/20 transition-all">
                            <p class="text-xs text-slate-500 mt-2">PNG, JPG o WEBP. Máximo 10MB.</p>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200 dark:border-slate-700/50">

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nombre de la Barbería *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $barbershop->nombre) }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Teléfono *</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 sm:text-sm font-medium">
                            +503
                        </span>
                        <input type="text" name="telefono" value="{{ old('telefono', str_replace('+503 ', '', $barbershop->telefono)) }}" required maxlength="8" class="w-full rounded-r-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-barber-dark text-slate-800 dark:text-slate-100 px-3 py-2 focus:ring-2 focus:ring-barber-accent">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-barber-accent hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection