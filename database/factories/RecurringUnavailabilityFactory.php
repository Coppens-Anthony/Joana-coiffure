<?php

namespace Database\Factories;

use App\Models\RecurringUnavailability;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecurringUnavailabilityFactory extends Factory
{
    protected $model = RecurringUnavailability::class;

    public function definition(): array
    {
        return [
            'days_of_week' => [6, 0],
            'start_time' => '09:00',
            'end_time' => '18:00',
            'starts_on' => now(),
        ];
    }
}
