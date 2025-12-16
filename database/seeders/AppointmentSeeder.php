<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener pacientes (usuarios con rol patient)
        $patients = User::where('role', 'patient')->get();
        
        // Obtener todos los doctores
        $doctors = Doctor::all();

        if ($patients->isEmpty() || $doctors->isEmpty()) {
            $this->command->warn('No hay pacientes o doctores disponibles para crear citas.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'canceled', 'attended'];
        $reasons = [
            'Consulta general',
            'Control de rutina',
            'Dolor abdominal',
            'Seguimiento post-operatorio',
            'Chequeo anual',
            'Dolor de cabeza persistente',
            'Revisión de exámenes',
            'Consulta de especialidad'
        ];

        // Crear 15 citas de ejemplo
        for ($i = 0; $i < 15; $i++) {
            $appointmentDate = Carbon::now()->addDays(rand(-15, 30));
            $hour = rand(9, 16);
            $appointmentDate->setTime($hour, 0, 0);

            Appointment::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date_time' => $appointmentDate,
                'status' => $statuses[array_rand($statuses)],
                'consultation_reason' => $reasons[array_rand($reasons)],
                'notes' => rand(0, 1) ? 'Paciente refiere ' . $reasons[array_rand($reasons)] : null,
            ]);
        }
    }
}
