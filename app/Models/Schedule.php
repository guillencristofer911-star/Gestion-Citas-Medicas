<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [

        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'interval_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
