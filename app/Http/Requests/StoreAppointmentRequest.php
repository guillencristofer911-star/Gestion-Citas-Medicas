<?php


namespace App\Http\Requests;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\Guard;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Autorizar que cualquier usuario autenticado pueda hacer la solicitud
     * (el controlador verificará que sea SU PROPIA cita)
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var Guard $auth */
        $auth = auth();
        return $auth->check();
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'consultation_reason' => 'required|string|min:10|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->failed()) {
                return;
            }

            $doctor = Doctor::find($this->doctor_id);
            $appointmentDate = Carbon::createFromFormat('Y-m-d', $this->appointment_date);
            $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
            $now = Carbon::now();
            if ($appointmentDateTime->isPast()) {
                $validator->errors()->add(
                    'appointment_time',
                    'No puedes agendar citas en horarios que ya pasaron. Hora actual: ' . $now->format('H:i')
                );
                return;
            }
            
            //  Validar que el doctor atiende ese día
            $dayName = strtolower($appointmentDate->format('l'));
            
            $schedule = Schedule::where('doctor_id', $doctor->id)
                ->where('day_of_week', $dayName)
                ->where('is_active', true)
                ->first();

            if (!$schedule) {
                $validator->errors()->add(
                    'appointment_date',
                    "El Dr. {$doctor->name} no atiende los " . $this->getSpanishDayName($dayName)
                );
                return;
            }

            //  Validar que la hora está en el rango
            $requestedTime = $this->appointment_time;
            
            if (!$this->isTimeInRange($requestedTime, $schedule->start_time, $schedule->end_time)) {
                $validator->errors()->add(
                    'appointment_time',
                    "La hora debe estar entre {$schedule->start_time} y {$schedule->end_time}"
                );
                return;
            }

            // Validar que no hay cita duplicada

            $datetime = $this->appointment_date . ' ' . $this->appointment_time . ':00';

            $doctoHasAppointment = Appointment::where('doctor_id', $doctor->id)
                ->where(function($querry){
                    $datetime = $this->appointment_date . ' ' . $this->appointment_time . ':00';
                    $querry->where ('appointment_date_time', $datetime);
                })
                ->exists();

            if ($doctoHasAppointment) {
                $validator->errors()->add(
                    'appointment_time',
                    "El Dr. {$doctor->name} ya tiene una cita agendada a las {$this->appointment_time} en la fecha seleccionada."
                );
                return;
            }

            $patientHasAppointment = Appointment::where('patient_id', $this->user()->id)
                ->where(function($querry){
                    $datetime = $this->appointment_date . ' ' . $this->appointment_time . ':00';
                    $querry->where ('appointment_date_time', $datetime);
                })
                ->where('status','!=','canceled')
                ->exists();
                if($patientHasAppointment){
                    $validator->errors()->add(
                        'appointment_time',
                        "Ya tienes una cita agendada a las {$this->appointment_time} en la fecha yo hora seleccionada seleccionada."
                    );
                    return;
                }

            
        });
    }

    private function isTimeInRange($time, $startTime, $endTime): bool
    {
        $time = strtotime($time);
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        
        return $time >= $start && $time <= $end;
    }

    private function getSpanishDayName($englishDay): string
    {
        $days = [
            'monday' => 'lunes',
            'tuesday' => 'martes',
            'wednesday' => 'miércoles',
            'thursday' => 'jueves',
            'friday' => 'viernes',
            'saturday' => 'sábado',
            'sunday' => 'domingo',
        ];
        
        return ucfirst($days[$englishDay] ?? $englishDay);
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Por favor, selecciona un médico.',
            'doctor_id.exists' => 'El médico seleccionado no existe.',
            'appointment_date.required' => 'La fecha es requerida.',
            'appointment_date.date_format' => 'El formato de fecha debe ser YYYY-MM-DD.',
            'appointment_date.after' => 'La fecha debe ser en el futuro.',
            'appointment_time.required' => 'La hora es requerida.',
            'appointment_time.date_format' => 'El formato de hora debe ser HH:MM.',
            'consultation_reason.required' => 'La razón de consulta es requerida.',
            'consultation_reason.min' => 'La razón debe tener al menos 10 caracteres.',
            'consultation_reason.max' => 'La razón no puede exceder 500 caracteres.',
            'appointment_date.after_or_equal' => 'La fecha debe ser hoy o en el futuro.',
            'appointment_time.required' => 'La hora es requerida.',
            'appointment_time.date_format' => 'El formato de hora debe ser HH:MM.',
        ];
    }
}
