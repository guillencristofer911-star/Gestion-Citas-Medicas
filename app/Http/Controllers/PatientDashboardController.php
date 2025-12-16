<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el dashboard del paciente con estadísticas y citas
     */
    public function index(): View
    {
        $user = Auth::user();

        // Estadísticas de citas
        $appointmentsToday = Appointment::where('patient_id', $user->id)
            ->whereDate('appointment_date_time', today())
            ->count();

        $pendingAppointments = Appointment::where('patient_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $confirmedAppointments = Appointment::where('patient_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $upcomingAppointments = Appointment::where('patient_id', $user->id)
            ->where('appointment_date_time', '>=', now())
            ->count();

        // Próximas citas
        $upcomingList = Appointment::where('patient_id', $user->id)
            ->where('appointment_date_time', '>=', now())
            ->with('doctor', 'doctor.user')
            ->orderBy('appointment_date_time', 'asc')
            ->paginate(15);

        // Historial de citas paginado
        $allAppointments = Appointment::where('patient_id', $user->id)
            ->with('doctor', 'doctor.user')
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(20);

        // Traducción de roles
        $roleTranslations = [
            'patient' => 'Paciente',
            'doctor' => 'Doctor',
            'admin' => 'Administrador',
        ];
        $userRole = $roleTranslations[$user->role] ?? $user->role;

        // Doctores activos disponibles
        $doctors = Doctor::where('active', true)
            ->with('user')
            ->get();

        return view('dashboard.patient.index', [
            'user' => $user,
            'userRole' => $userRole,
            'appointmentsToday' => $appointmentsToday,
            'pendingAppointments' => $pendingAppointments,
            'confirmedAppointments' => $confirmedAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'upcomingList' => $upcomingList,
            'allAppointments' => $allAppointments,
            'doctors' => $doctors,
        ]);
    }
}
