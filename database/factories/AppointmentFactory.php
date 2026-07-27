<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $startAt = Carbon::instance($this->faker->dateTimeThisMonth())
            ->setTime($this->faker->numberBetween(9, 17), $this->faker->randomElement([0, 30]));

        return [
            'uuid' => Str::uuid(),
            'client_id' => Client::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'message' => $this->faker->sentence(),
            'start_at' => $startAt,
            'end_at' => $startAt->copy()->addMinutes(60),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
