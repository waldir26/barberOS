@extends('layouts.app')

@section('title', 'Calendario de Citas')

@section('content')
<div class="max-w-7xl mx-auto" id="calendar-app" x-data="{ 
    showModal: false, 
    cita: {} 
}">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Calendario</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Visualiza las citas agendadas de la barbería.</p>
    </div>

    <!-- Contenedor del Calendario -->
    <div class="bg-white dark:bg-barber-card-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-3 md:p-6 overflow-hidden">
        <div id="calendar"></div>
    </div>

    <!-- MODAL DE DETALLES DE CITA -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-barber-card-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700/50">

                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700/50 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="calendar-check" class="w-5 h-5 text-barber-accent"></i>
                        Detalles de la Cita
                    </h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-lg text-barber-accent">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Cliente</p>
                            <p class="font-semibold text-slate-900 dark:text-white" x-text="cita.cliente"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-50 dark:bg-purple-500/10 rounded-lg text-purple-600 dark:text-purple-400">
                            <i data-lucide="scissors" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Servicio</p>
                            <p class="font-semibold text-slate-900 dark:text-white" x-text="cita.servicio + ' ($' + cita.precio + ')'"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-400">
                            <i data-lucide="user-square" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Barbero</p>
                            <p class="font-semibold text-slate-900 dark:text-white" x-text="cita.barbero"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-200 dark:border-slate-700/50">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> Fecha</p>
                            <p class="font-medium text-slate-900 dark:text-white" x-text="cita.fecha"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> Hora</p>
                            <p class="font-medium text-slate-900 dark:text-white" x-text="cita.hora"></p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-200 dark:border-slate-700/50">
                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mb-1"><i data-lucide="file-text" class="w-3 h-3"></i> Notas</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 p-2 rounded-lg" x-text="cita.notas"></p>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex justify-end">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var isMobile = window.innerWidth < 768;

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: isMobile ? 'listWeek' : 'dayGridMonth',
            locale: 'es',
            firstDay: 1, // Inicia la semana en Lunes
            headerToolbar: isMobile ? {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            } : {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            slotMinTime: '07:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short',
                hour12: true
            },
            dayHeaderFormat: {
                weekday: 'short',
                day: 'numeric'
            }, // Formato "Lun 19"
            events: '{{ route("app.appointments.calendar.data") }}',
            height: 'auto',
            dayMaxEvents: 2,
            eventDisplay: 'block',
            eventClick: function(info) {
                var alpineComponent = document.getElementById('calendar-app');
                if (alpineComponent) {
                    var data = alpineComponent._x_dataStack[0];
                    data.cita = info.event.extendedProps;
                    data.showModal = true;
                }
            }
        });

        calendar.render();
    });
</script>

<style>
    /* ==========================================
       ESTILOS PREMIUM Y RESPONSIVOS
       ========================================== */

    :root {
        --fc-border-color: #e2e8f0;
        --fc-button-bg-color: #ffffff;
        --fc-button-text-color: #475569;
        --fc-button-border-color: #e2e8f0;
        --fc-button-hover-bg-color: #f1f5f9;
        --fc-today-bg-color: rgba(59, 130, 246, 0.05);
        --fc-page-bg-color: transparent;
    }

    .dark {
        --fc-border-color: #334155;
        --fc-button-bg-color: #1e293b;
        --fc-button-text-color: #cbd5e1;
        --fc-button-border-color: #334155;
        --fc-button-hover-bg-color: #334155;
        --fc-today-bg-color: rgba(59, 130, 246, 0.15);
    }

    .fc {
        font-family: inherit;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsividad del Toolbar */
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    @media (min-width: 768px) {
        .fc .fc-toolbar {
            flex-direction: row;
            justify-content: space-between;
        }
    }

    .fc .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    @media (min-width: 768px) {
        .fc .fc-toolbar-chunk {
            width: auto;
        }
    }

    /* Título del mes */
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        text-transform: capitalize;
        white-space: nowrap;
        letter-spacing: -0.02em;
    }

    .dark .fc .fc-toolbar-title {
        color: #f8fafc;
    }

    /* Botones superiores */
    .fc .fc-button-primary {
        background-color: var(--fc-button-bg-color);
        border-color: var(--fc-button-border-color);
        color: var(--fc-button-text-color);
        font-weight: 600;
        text-transform: capitalize;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        transition: all 0.2s ease-in-out;
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    .fc .fc-button-primary:hover {
        background-color: var(--fc-button-hover-bg-color);
        color: var(--fc-button-text-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #3B82F6 !important;
        border-color: #3B82F6 !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }

    /* Encabezados de días */
    .fc .fc-col-header-cell {
        background-color: #f8fafc;
        padding: 8px 0;
    }

    .dark .fc .fc-col-header-cell {
        background-color: #1e293b;
    }

    .fc .fc-col-header-cell-cushion {
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        transition: color 0.2s;
        white-space: nowrap;
        /* Evita que se parta el texto en varias líneas */
    }

    .dark .fc .fc-col-header-cell-cushion {
        color: #94a3b8;
    }

    /* Celdas de los días (Efecto Hover) */
    .fc .fc-daygrid-day {
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .fc .fc-daygrid-day:hover {
        background-color: rgba(59, 130, 246, 0.03) !important;
    }

    .dark .fc .fc-daygrid-day:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }

    /* Números de los días */
    .fc .fc-daygrid-day-number {
        color: #334155;
        font-weight: 600;
        padding: 6px;
        font-size: 0.85rem;
        transition: all 0.2s;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 4px;
    }

    .dark .fc .fc-daygrid-day-number {
        color: #e2e8f0;
    }

    .fc .fc-daygrid-day:hover .fc-daygrid-day-number {
        background-color: #f1f5f9;
        color: #3B82F6;
    }

    .dark .fc .fc-daygrid-day:hover .fc-daygrid-day-number {
        background-color: #334155;
        color: #60A5FA;
    }

    /* Día de hoy resaltado */
    .fc .fc-day-today .fc-daygrid-day-number {
        color: #ffffff;
        background-color: #3B82F6;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.4);
    }

    /* Estilo de los eventos (Tarjetitas) */
    .fc-event {
        border-radius: 6px !important;
        border: none !important;
        padding: 3px 6px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        margin: 2px 4px !important;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .fc-event:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.2), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        filter: brightness(1.1);
    }

    .fc-event-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: var(--fc-border-color);
    }

    /* TRUCO MAESTRO PARA MÓVILES: Hace que las columnas sean anchas y se pueda deslizar */
    @media (max-width: 768px) {
        .fc-view-harness {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .fc .fc-view {
            min-width: 700px;
            /* Obliga a las columnas a tener este ancho */
        }

        .fc .fc-toolbar-title {
            font-size: 1.1rem;
            /* Título un poco más pequeño en celular */
        }
    }
</style>
@endsection