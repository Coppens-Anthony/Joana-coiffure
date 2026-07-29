<?php

namespace Database\Factories;

use App\Models\Unavailability;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UnavailabilityFactory extends Factory
{
    protected $model = Unavailability::class;

    public function definition(): array
    {
        return [
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now(),
            'user_id' => User::inRandomOrder()->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
