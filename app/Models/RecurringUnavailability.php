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
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'starts_on' => 'date',
    ];

    public function getDaysOfWeekLabels(): array
    {
        $labels = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $order = [1, 2, 3, 4, 5, 6, 0];

        return array_map(
            fn ($day) => $labels[$day],
            array_filter($order, fn ($day) => in_array($day, $this->days_of_week))
        );
    }
}
