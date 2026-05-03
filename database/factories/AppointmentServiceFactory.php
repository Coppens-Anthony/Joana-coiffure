<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentServiceFactory extends Factory
{
    protected $model = AppointmentService::class;

    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()->id,
            'service_id' => Service::inRandomOrder()->first()->id,
        ];
    }
}
