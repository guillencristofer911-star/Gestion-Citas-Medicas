<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;
use App\Models\Doctor;

class Appointment extends Model
{
    //campos que se pueden llenar
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date_time',
        'status',
        'consultation_reason',
        'notes'
    ];

    protected $casts = [
        'appointment_date_time' => 'datetime',
    ];
    // Relación con el modelo User (paciente)
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relación con el modelo Doctor
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

}
