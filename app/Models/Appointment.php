<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'client_id',
        'start_at',
        'end_at',
    ];

    public function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'updated_at' => 'date',
            'created_at' => 'date',
        ];
    }

    public function formatDate($field): string
    {
        return Carbon::parse($this->attributes[$field])
            ->isoFormat('D MMMM YYYY');
    }

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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'appointment_service', 'appointment_id', 'service_id')->withTrashed();
    }
}
