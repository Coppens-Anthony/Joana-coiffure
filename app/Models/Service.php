<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'duration',
        'price',
        'desc',
    ];

    public function durationFormat($field): string
    {
        if ($field >= 60) {
            $hours = intdiv($field, 60);
            $minutes = $field % 60;

            if ($minutes > 0) {
                $minutesPadded = str_pad($minutes, 2, '0', STR_PAD_LEFT);
                return "{$hours}h{$minutesPadded}";
            }

            return "{$hours}h";
        }

        return "{$field} minutes";
    }

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_service', 'service_id', 'appointment_id');
    }
}
