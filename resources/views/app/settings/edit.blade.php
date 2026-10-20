@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<!-- Envolvemos todo en x-data para controlar el modal -->
<div class="max-w-3xl mx-auto" x-data="{ deleteModal: false }">
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
                <div x-data="{ logoPreview: null }">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Logo de la Barbería</label>
                    <div class="flex flex-wrap items-center gap-6">

                        <!-- Contenedor de Previsualización -->
                        <div class="w-24 h-24 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" class="w-full h-full object-contain p-2">
                            </template>

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

                        <div class="flex-1 min-w-[200px]">
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

                            <!-- Botón para abrir el modal de eliminar -->
                            @if($barbershop->logo)
                            <button type="button" x-on:click="deleteModal = true" class="mt-3 text-sm text-red-500 hover:text-red-700 flex items-center gap-1 transition-colors font-medium">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Eliminar logo actual
                            </button>
                            @endif
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

        <!-- Formulario oculto para procesar la eliminación -->
        <form id="delete-logo-form" action="{{ route('app.settings.delete-logo') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR LOGO -->
    <div x-show="deleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="deleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" x-on:click="deleteModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="deleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-barber-card-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700/50">

                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-500/20 rounded-full mb-4">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-center text-slate-900 dark:text-white mb-2">Eliminar Logo</h3>
                    <p class="text-sm text-center text-slate-500 dark:text-slate-400">
                        ¿Estás seguro de que deseas eliminar el logo de la barbería? Se restaurará el nombre de la barbería como texto.
                    </p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex justify-center gap-3 rounded-b-2xl">
                    <button type="button" x-on:click="deleteModal = false" class="px-5 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="button" x-on:click="document.getElementById('delete-logo-form').submit()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-5 rounded-xl transition-all shadow-sm">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection