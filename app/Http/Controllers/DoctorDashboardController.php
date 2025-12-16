<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            ->paginate(15);

        // Historial de citas paginado
        $allAppointments = $doctor->appointments()
            ->with('patient')
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(20);
        
        // Traducción de roles
        $roleTranslations = [
            'patient' => 'Paciente',
            'doctor' => 'Doctor',
            'admin' => 'Administrador',
        ];
        $userRole = $roleTranslations[$user->role] ?? $user->role;

        // ===== NUEVO: DATOS PARA MI AGENDA (RF-13) =====
        
        // Datos para vista diaria
        $todayDate = Carbon::now()->format('Y-m-d');
        $dailySchedule = $this->generateDailySchedule($doctor->id, $todayDate);
        $todayAppointments = $doctor->appointments()
            ->whereDate('appointment_date_time', $todayDate)
            ->count();
        $todayAvailability = $this->calculateAvailableHours($doctor->id, $todayDate);
        
        // Datos para vista semanal
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklySchedule = $this->generateWeeklySchedule($doctor->id, $startOfWeek, $endOfWeek);
        $weeklyAppointments = $doctor->appointments()
            ->whereBetween('appointment_date_time', [$startOfWeek, $endOfWeek])
            ->count();
        $weeklyConfirmed = $doctor->appointments()
            ->whereBetween('appointment_date_time', [$startOfWeek, $endOfWeek])
            ->where('status', 'confirmed')
            ->count();
        $weeklyPending = $doctor->appointments()
            ->whereBetween('appointment_date_time', [$startOfWeek, $endOfWeek])
            ->where('status', 'pending')
            ->count();
        $weeklyHours = 45;

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
            'dailySchedule' => $dailySchedule,
            'todayAppointments' => $todayAppointments,
            'todayAvailability' => $todayAvailability,
            'weeklySchedule' => $weeklySchedule,
            'weeklyAppointments' => $weeklyAppointments,
            'weeklyConfirmed' => $weeklyConfirmed,
            'weeklyPending' => $weeklyPending,
            'weeklyHours' => $weeklyHours,
        ]);
    }

    /**
     * Genera la agenda diaria con todos los slots horarios
     * RF-13: Visualización de agenda diaria
     */
    private function generateDailySchedule($doctorId, $date)
    {
        $schedule = [];
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date_time', $date)
            ->with('patient')
            ->get();
        
        // Generar slots de 1 hora desde 8am a 5pm
        for ($hour = 8; $hour < 17; $hour++) {
            $startTime = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            $endTime = str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00';
            
            // Buscar si hay una cita en este horario
            $appointment = $appointments->first(function ($apt) use ($hour, $date) {
                $aptHour = Carbon::parse($apt->appointment_date_time)->hour;
                $aptDate = $apt->appointment_date_time->format('Y-m-d');
                return $aptHour == $hour && $aptDate == $date;
            });
            
            $schedule[] = [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $appointment ? 'booked' : 'available',
                'appointment' => $appointment,
            ];
        }
        
        return $schedule;
    }

    /**
     * Genera la agenda semanal con información de los 7 días
     * RF-13: Visualización de agenda semanal
     */
    private function generateWeeklySchedule($doctorId, $startOfWeek, $endOfWeek)
    {
        $schedule = [];
        $daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        
        for ($i = 0; $i < 7; $i++) {
            $currentDate = $startOfWeek->copy()->addDays($i);
            $dateString = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->dayOfWeek; // 0 = domingo, 1 = lunes, ..., 6 = sábado
            
            // Obtener citas del día
            $appointments = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date_time', $dateString)
                ->with('patient')
                ->orderBy('appointment_date_time', 'asc')
                ->get();
            
            // Determinar si es día laboral (lunes a viernes = 1 a 5)
            $isWorkDay = in_array($dayOfWeek, [1, 2, 3, 4, 5]);
            
            // Calcular horas de atención
            if ($dayOfWeek == 5) { // Viernes
                $startHour = '08:00';
                $endHour = '16:00';
                $totalHours = 8;
            } elseif ($isWorkDay) {
                $startHour = '08:00';
                $endHour = '17:00';
                $totalHours = 9;
            } else {
                $startHour = '-';
                $endHour = '-';
                $totalHours = 0;
            }
            
            $schedule[] = [
                'date' => $dateString,
                'date_short' => $currentDate->format('d/m'),
                'day_name' => $daysOfWeek[$i],
                'start_time' => $startHour,
                'end_time' => $endHour,
                'status' => $isWorkDay ? 'active' : 'inactive',
                'appointments_count' => $appointments->count(),
                'available_hours' => max(0, $totalHours - $appointments->count()),
                'appointments' => $appointments->map(function ($apt) {
                    return [
                        'time' => $apt->appointment_date_time->format('H:i'),
                        'patient' => $apt->patient->name ?? 'Paciente',
                    ];
                })->toArray(),
            ];
        }
        
        return $schedule;
    }

    /**
     * Calcula las horas disponibles en un día
     */
    private function calculateAvailableHours($doctorId, $date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        
        // Determinar horas totales según el día
        if ($dayOfWeek == 5) { // Viernes
            $totalHours = 8;
        } elseif (in_array($dayOfWeek, [1, 2, 3, 4])) { // Lunes a Jueves
            $totalHours = 9;
        } else { // Fin de semana
            return '0 hrs';
        }
        
        // Contar citas del día
        $appointmentCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date_time', $date)
            ->count();
        
        $availableHours = max(0, $totalHours - $appointmentCount);
        
        return $availableHours . ' hrs';
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
