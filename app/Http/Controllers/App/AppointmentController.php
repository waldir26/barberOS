<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Por defecto mostramos las citas de hoy, o las de la fecha que se elija
        $fecha = $request->get('fecha', Carbon::today()->toDateString());

        $appointments = Appointment::with(['client', 'barber', 'service'])
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        // Datos para el modal de crear cita
        $clients = Client::orderBy('nombre')->get();
        $barbers = Barber::where('activo', true)->orderBy('nombre')->get();
        $services = Service::where('activo', true)->orderBy('nombre')->get();

        return view('app.appointments.index', compact('appointments', 'fecha', 'clients', 'barbers', 'services'));
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

                    // Verificamos si se cruzan las horas
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

        return redirect()->back()->with('success', 'Cita agendada exitosamente.');
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
