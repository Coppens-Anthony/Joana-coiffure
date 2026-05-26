<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringUnavailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'days_of_week',
        'start_time',
        'end_time',
        'starts_on',
        'ends_on',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'starts_on' => 'date',
        'ends_on' => 'date',
    ];
}
