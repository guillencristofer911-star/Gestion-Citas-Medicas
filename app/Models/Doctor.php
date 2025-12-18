<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'license_number',
        'specialty',
        'biography',
        'photo_url',
        'active',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación muchos a uno con User
     * Un doctor pertenece a un usuario
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación uno a muchos con Appointment
     * Un doctor puede tener muchas citas
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Relación uno a muchos con Schedule
     * Un doctor puede tener muchos horarios
     *
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
