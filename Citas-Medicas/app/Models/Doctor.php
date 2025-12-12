<?php 

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model 
{
    protected $fillable = [
        'user_id',
        'license_number',
        'specialty',
        'biography',
        'photo_url',
        'active',

    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    //Relacion con el modelo User
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    //Relacion con el modelo Appointment

    public function appointments(): HasMany
    {
       return $this->hasMany(Appointment::class,'doctor_id');
    }


}