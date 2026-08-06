<?php

namespace Database\Factories;

use App\Models\RecurringUnavailability;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RecurringUnavailabilityFactory extends Factory
{
    protected $model = RecurringUnavailability::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'days_of_week' => [6, 0],
            'start_time' => config('app.hours.hour_start'),
            'end_time' => config('app.hours.hour_end'),
            'starts_on' => now(),
            'ends_on' => now()->addMonths(6),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
