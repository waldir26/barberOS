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

        $query = Appointment::with(['client', 'barber', 'service'])
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
        $service = Service::findOrFail($validated['service_id']);

        // Validación de choque de horarios (si el servicio tiene duración)
        if ($service->duracion) {
            $nuevaCitaFin = $fechaHora->copy()->addMinutes($service->duracion);

            $choque = Appointment::where('barber_id', $validated['barber_id'])
                ->whereDate('fecha_hora', $validated['fecha'])
                ->where('estado', '!=', 'cancelada')
                ->get()
                ->contains(function ($citaExistente) use ($fechaHora, $nuevaCitaFin) {
                    $citaExistenteFin = $citaExistente->fecha_hora->copy()->addMinutes($citaExistente->service->duracion ?? 0);
                    return ($fechaHora < $citaExistenteFin) && ($nuevaCitaFin > $citaExistente->fecha_hora);
                });

            if ($choque) {
                return back()->withInput()->withErrors(['hora' => 'El barbero ya tiene una cita en ese horario. Por favor, elige otra hora.']);
            }
        }

        Appointment::create([
            'client_id' => $validated['client_id'],
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'fecha_hora' => $fechaHora,
            'estado' => 'pendiente',
            'notas' => $validated['notas'],
        ]);

        return redirect()->route('app.appointments.index')->with('success', 'Cita agendada exitosamente.');
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
}
