<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Doctor;
use App\Models\Appointment;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',   
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación con el modelo Doctor (si el usuario tiene perfil de doctor)
    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    // Relación con el modelo Appointment (como paciente)
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
