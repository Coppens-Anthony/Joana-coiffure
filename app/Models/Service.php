<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

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
}
