<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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
        if (!$doctor){
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
        //citas pendientes

        // Historial de citas paginado
        $allAppointments = $doctor->appointments()
            ->with('patient')
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(10);
        
        
        //Traducción de roles
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
    }


