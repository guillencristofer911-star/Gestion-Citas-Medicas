<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\User;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Obtener datos para mostrar en la vista
        $doctors = Doctor::withTrashed()->with('user')->get();
        $schedules = Schedule::with('doctor.user')->get();
        
        // CAMBIO: patient y doctor SON models, no tienen .user
        $appointments = Appointment::with('patient', 'doctor')
            ->orderBy('appointment_date_time', 'desc')
            ->get();
        
        $users = User::all();
        
        // Estadísticas
        $totalDoctors = Doctor::count();
        $totalPatients = User::where('role', 'patient')->count();
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        
        return view('dashboard.admin.index', compact(
            'doctors',
            'schedules',
            'appointments',
            'users',
            'totalDoctors',
            'totalPatients',
            'totalAppointments',
            'pendingAppointments'
        ));
    }
}
