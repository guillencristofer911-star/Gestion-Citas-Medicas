<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

//guarda una neva cita, recibe datos del formulario y guarda en BD

public function store (Request $request): RedirectResponse
{
    //validar datos
    $validate = $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'appointment_date' => 'required|date|after:today',
        'appointment_time' => 'required|date_format:H:i',
        'consultation_reason'=> 'required|string|min:10|max:500',

    ]);

    //combinar fecha y hora
    $appointment_date_time = $validate['appointment_date'] . ' ' . $validate['appointment_time'];

    //crear nueva cita
    Appointment::create([
        'patient_id' => Auth::id(),
        'doctor_id' => $validate['doctor_id'],
        'appointment_date_time' => $appointment_date_time,
        'description' => $validate['consultation_reason'],
        'status' => 'pending',
    ]);

    //Redirigir con mensaje de éxito
    return redirect()
    ->route ('patient.appointments.index')
    ->with ('success', 'Cita creada exitosamente y está pendiente de confirmación.');
}

    //cancelar citas
    public function cancel(Appointment $appointment): RedirectResponse
    {
        //verificar que la cita pertenece al paciente autenticado
        
        if ($appointment->patient_id !== Auth::id()){
            abort(403, 'No autorizado para cancelar esta cita.');
        }

        //solo puede cancelar citas pendientes o confirmadas
        if (!in_array ($appointment->status, ['pending', 'confirmed'])){
            return redirect ()
            ->back ()
            ->with ('error', 'Solo se pueden cancelar citas pendientes o confirmadas.');
        }

        //cambiar estado a cancelada
        $appointment->status = 'canceled';
        $appointment->save();

        return redirect ()
        ->route ('patient.appointments.index')
        ->with ('success', 'Cita cancelada exitosamente.');
    }
}
