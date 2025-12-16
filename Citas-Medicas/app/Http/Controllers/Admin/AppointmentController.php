<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(20);
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Obtener detalles de una cita (AJAX)
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user']);
        
        // Si es petición AJAX, devolver JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $appointment->id,
                    'patient' => [
                        'id' => $appointment->patient->id,
                        'name' => $appointment->patient->name,
                        'email' => $appointment->patient->email,
                    ],
                    'doctor' => [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->user->name,
                        'specialty' => $appointment->doctor->specialty,
                    ],
                    'appointment_date_time' => $appointment->appointment_date_time->format('Y-m-d H:i'),
                    'date' => $appointment->appointment_date_time->format('d/m/Y'),
                    'time' => $appointment->appointment_date_time->format('H:i A'),
                    'status' => $appointment->status,
                    'status_label' => ucfirst($appointment->status),
                    'consultation_reason' => $appointment->consultation_reason ?? 'No especificado',
                    'notes' => $appointment->notes,
                    'created_at' => $appointment->created_at->format('d/m/Y H:i'),
                ]
            ]);
        }
        
        // Si es petición normal, devolver vista
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Buscar/filtrar citas (AJAX)
     */
    public function search(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user']);

        // Filtrar por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrar por rango de fechas
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date_time', '<=', $request->date_to);
        }

        // Buscar por nombre de paciente
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Buscar por nombre de doctor
        if ($request->filled('doctor_name')) {
            $query->whereHas('doctor.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        $appointments = $query->orderBy('appointment_date_time', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $appointments->map(function($apt) {
                return [
                    'id' => $apt->id,
                    'patient_name' => $apt->patient->name,
                    'doctor_name' => $apt->doctor->user->name,
                    'date' => $apt->appointment_date_time->format('d/m/Y'),
                    'time' => $apt->appointment_date_time->format('H:i'),
                    'status' => $apt->status,
                    'status_label' => ucfirst($apt->status),
                ];
            }),
            'pagination' => [
                'current_page' => $appointments->currentPage(),
                'last_page' => $appointments->lastPage(),
                'per_page' => $appointments->perPage(),
                'total' => $appointments->total(),
            ]
        ]);
    }
}
