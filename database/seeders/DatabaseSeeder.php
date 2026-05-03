<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => Hash::make('password'),
        ]);

        Client::factory(10)->create();
        Service::factory(10)->create();

        Appointment::factory(20)->create()->each(function ($appointment) {
            $nbServices = rand(1, 2);
            for ($i = 0; $i < $nbServices; $i++) {
                AppointmentService::factory()->create([
                    'appointment_id' => $appointment->id,
                    'service_id' => Service::inRandomOrder()->first()->id,
                ]);
            }
        });
    }
}
