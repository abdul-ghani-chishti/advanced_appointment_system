<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'application_id',
        'appointment_status_id',
        'appointment_date',
        'start_time',
        'end_time',
        'location',
        'instructions',
        'email_sent_at',
        'confirmed_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'email_sent_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(
            AppointmentStatus::class,
            'appointment_status_id'
        );
    }
}
