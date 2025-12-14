<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            abort(403, 'Acceso no autorizado.');
        }
        
        // Estadísticas
        $totalAppointments = $doctor->appointments()->count();
        $pendingAppointments = $doctor->appointments()
            ->where('status', 'pending')->count();
        $confirmedAppointments = $doctor->appointments()
            ->where('status', 'confirmed')->count();
        $attendedAppointments = $doctor->appointments()
            ->where('status', 'attended')->count();
        $upcomingAppointments = $doctor->appointments()
            ->where('appointment_date_time', '>=', now())->count();

        // Próximas citas
        $upcomingList = $doctor->appointments()
            ->where('appointment_date_time', '>=', now())
            ->with('patient')
            ->orderBy('appointment_date_time', 'asc')
            ->paginate(10);

        // Historial de citas paginado
        $allAppointments = $doctor->appointments()
            ->with('patient')
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(10);
        
        // Traducción de roles
        $roleTranslations = [
            'patient' => 'Paciente',
            'doctor' => 'Doctor',
            'admin' => 'Administrador',
        ];
        $userRole = $roleTranslations[$user->role] ?? $user->role;

        return view('dashboard.doctor.index', [
            'user' => $user,
            'doctor' => $doctor,
            'userRole' => $userRole,
            'totalAppointments' => $totalAppointments,
            'pendingAppointments' => $pendingAppointments,
            'confirmedAppointments' => $confirmedAppointments,
            'attendedAppointments' => $attendedAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'upcomingList' => $upcomingList,
            'allAppointments' => $allAppointments,
        ]);
    }

    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        // Validar que el doctor autenticado sea el dueño de esta cita
        $doctor = Auth::user()->doctor;
        
        if ($appointment->doctor_id !== $doctor->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar esta cita.'
            ], 403);
        }

        // Validar los datos recibidos
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,attended,canceled',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Actualizar el estado de la cita
        $appointment->status = $validated['status'];
        
        // Si hay notas, agregarlas
        if (!empty($validated['notes'])) {
            $appointment->notes = $validated['notes'];
        }
        
        $appointment->save();

        // Retornar respuesta JSON exitosa
        return response()->json([
            'success' => true,
            'message' => 'Estado de la cita actualizado correctamente.',
            'appointment' => $appointment
        ]);
    }
}
