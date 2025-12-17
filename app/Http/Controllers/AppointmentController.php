<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAppointmentRequest;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Guarda una nueva cita médica
     * Recibe datos del formulario y guarda en la base de datos
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        // Combinar fecha y hora
        $appointment_date_time = $request->appointment_date . ' ' . $request->appointment_time;

        // Crear nueva cita
        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date_time' => $appointment_date_time,
            'description' => $request->consultation_reason,
            'status' => 'pending',
        ]);

        // Redirigir con mensaje de éxito
        return redirect()
            ->to(route('patient.dashboard') . '#appointments')
            ->with('success', 'Cita médica solicitada exitosamente.');
    }

    /**
     * Cancelar una cita médica
     * Solo permite cancelar citas pendientes o confirmadas
     */
    public function cancel(Appointment $appointment): RedirectResponse
    {
        // Verificar que la cita pertenece al paciente autenticado
        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'No autorizado para cancelar esta cita.');
        }

        // Solo puede cancelar citas pendientes o confirmadas
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return redirect()
                ->back()
                ->with('error', 'Solo se pueden cancelar citas pendientes o confirmadas.');
        }

        // Cambiar estado a cancelada
        $appointment->status = 'canceled';
        $appointment->save();

        return redirect()
            ->to(route('patient.dashboard') . '#appointments')
            ->with('success', 'Cita médica cancelada exitosamente.');
    }
}