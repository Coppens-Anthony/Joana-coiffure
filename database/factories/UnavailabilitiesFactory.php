<?php

namespace Database\Factories;

use App\Models\Unavailability;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UnavailabilitiesFactory extends Factory
{
    protected $model = Unavailability::class;

    public function definition(): array
    {
        return [
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
