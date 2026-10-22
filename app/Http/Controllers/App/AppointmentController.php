<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->toDateString());
        $search = $request->get('search');

        // Filtramos para que solo traiga citas donde el cliente y el servicio existan
        $query = Appointment::with(['client', 'barber', 'service'])
            ->whereHas('client')
            ->whereHas('service')
            ->whereDate('fecha_hora', $fecha);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($qClient) use ($search) {
                    $qClient->where('nombre', 'like', "%{$search}%");
                })->orWhereHas('barber', function ($qBarber) use ($search) {
                    $qBarber->where('nombre', 'like', "%{$search}%");
                });
            });
        }

        $appointments = $query->orderBy('fecha_hora', 'asc')->get();

        // Datos para el modal de edición
        $barbers = Barber::where('activo', true)->orderBy('nombre')->get();
        $services = Service::where('activo', true)->orderBy('nombre')->get();

        return view('app.appointments.index', compact('appointments', 'fecha', 'search', 'barbers', 'services'));
    }

    public function create()
    {
        $clients = Client::orderBy('nombre')->get();
        $barbers = Barber::where('activo', true)->orderBy('nombre')->get();
        $services = Service::where('activo', true)->orderBy('nombre')->get();

        return view('app.appointments.create', compact('clients', 'barbers', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'barber_id' => ['required', 'exists:barbers,id'],
            'service_id' => ['required', 'exists:services,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'notas' => ['nullable', 'string'],
        ]);

        $fechaHora = Carbon::parse($validated['fecha'] . ' ' . $validated['hora']);

        // Validación: El barbero no puede tener 2 citas a la misma hora exacta
        $choque = Appointment::where('barber_id', $validated['barber_id'])
            ->where('fecha_hora', $fechaHora)
            ->where('estado', '!=', 'cancelada')
            ->exists();

        if ($choque) {
            return back()->withInput()->withErrors(['hora' => 'El barbero ya tiene una cita agendada exactamente a esta hora. Por favor, elige otra hora.']);
        }

        Appointment::create([
            'client_id' => $validated['client_id'],
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'fecha_hora' => $fechaHora,
            'estado' => 'pendiente',
            'notas' => $validated['notas'],
        ]);

        return redirect()->route('app.appointments.index', ['fecha' => $validated['fecha']])
            ->with('success', 'Cita agendada exitosamente.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'barber_id' => ['required', 'exists:barbers,id'],
            'service_id' => ['required', 'exists:services,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'notas' => ['nullable', 'string'],
        ]);

        $fechaHora = Carbon::parse($validated['fecha'] . ' ' . $validated['hora']);

        // Validación estricta al editar (ignorando la cita actual)
        $choque = Appointment::where('barber_id', $validated['barber_id'])
            ->where('fecha_hora', $fechaHora)
            ->where('estado', '!=', 'cancelada')
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($choque) {
            return back()->withInput()->withErrors(['hora' => 'El barbero ya tiene otra cita agendada a esta misma hora.']);
        }

        $appointment->update([
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'fecha_hora' => $fechaHora,
            'notas' => $validated['notas'],
        ]);

        return redirect()->back()->with('success', 'Cita actualizada correctamente.');
    }

    public function pay(Appointment $appointment)
    {
        // Registrar el ingreso en la caja
        Transaction::create([
            'tipo' => 'ingreso_servicio',
            'descripcion' => 'Cobro: ' . $appointment->service->nombre . ' (' . $appointment->client->nombre . ')',
            'monto' => $appointment->service->precio,
            'appointment_id' => $appointment->id,
        ]);

        // Marcar la cita como completada
        $appointment->update(['estado' => 'completada']);

        return redirect()->back()->with('success', 'Pago registrado exitosamente y cita completada.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:pendiente,completada,cancelada']
        ]);

        $appointment->update(['estado' => $validated['estado']]);

        return redirect()->back()->with('success', 'Estado de la cita actualizado.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->back()->with('success', 'Cita eliminada.');
    }

    public function calendar()
    {
        return view('app.appointments.calendar');
    }

    public function calendarData()
    {
        $appointments = Appointment::with(['client', 'barber', 'service'])->get();

        // Paleta de colores para los barberos
        $coloresBarberos = [
            '#3B82F6', // Azul
            '#10B981', // Verde Esmeralda
            '#8B5CF6', // Morado
            '#F59E0B', // Naranja
            '#EF4444', // Rojo
            '#06B6D4', // Cyan
            '#EC4899', // Rosa
            '#14B8A6', // Teal
        ];

        $events = $appointments->map(function ($cita) use ($coloresBarberos) {
            // Color fijo según el barbero
            $colorIndex = $cita->barber_id % count($coloresBarberos);
            $color = $coloresBarberos[$colorIndex];

            // Si está cancelada, la pintamos de gris
            if ($cita->estado === 'cancelada') {
                $color = '#94A3B8';
            }

            return [
                'id' => $cita->id,
                // Agregamos la hora al título para mejor lectura
                'title' => $cita->fecha_hora->format('h:i A') . ' - ' . $cita->client->nombre,
                'start' => $cita->fecha_hora->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'cliente' => $cita->client->nombre,
                    'servicio' => $cita->service->nombre,
                    'precio' => number_format($cita->service->precio, 2),
                    'barbero' => $cita->barber->nombre,
                    'estado' => ucfirst($cita->estado),
                    'notas' => $cita->notas ?? 'Sin notas',
                    'hora' => $cita->fecha_hora->format('h:i A'),
                    'fecha' => $cita->fecha_hora->format('d/m/Y'),
                ]
            ];
        });

        return response()->json($events);
    }

    public function checkAvailability(Request $request)
    {
        $fecha = $request->get('fecha');
        $hora = $request->get('hora');
        $barber_id = $request->get('barber_id');

        if (!$fecha || !$hora || !$barber_id) {
            return response()->json(['disponible' => true]);
        }

        $fechaHora = Carbon::parse($fecha . ' ' . $hora);

        $ocupado = Appointment::where('barber_id', $barber_id)
            ->where('fecha_hora', $fechaHora)
            ->where('estado', '!=', 'cancelada')
            ->first();

        if ($ocupado) {
            return response()->json([
                'disponible' => false,
                'cliente' => $ocupado->client->nombre
            ]);
        }

        return response()->json(['disponible' => true]);
    }
}
