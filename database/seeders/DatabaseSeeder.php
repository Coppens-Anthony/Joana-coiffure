<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
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

        $services = [
            [
                'name' => 'Homme',
                'duration' => 20,
                'price' => 15,
                'desc' => 'Réalisation d’une coupe homme aux ciseaux et/ou à la tondeuse selon le style souhaité. Travail des longueurs, des contours et finition soignée pour un résultat propre et facile à coiffer.',
            ],
            [
                'name' => 'Enfants',
                'duration' => 20,
                'price' => 20,
                'desc' => 'Coupe enfant aux ciseaux et/ou à la tondeuse, adaptée à l’âge et à la nature du cheveu. Résultat naturel, propre et facile à entretenir avec une finition douce.',
            ],
            [
                'name' => 'Coupe + brushing',
                'duration' => 40,
                'price' => 40,
                'desc' => 'Coupe personnalisée suivie d’un brushing adapté à votre style. Travail de la forme et du volume pour un rendu soigné et structuré.',
            ],
            [
                'name' => 'Coloration + brushing',
                'duration' => 90,
                'price' => 75,
                'desc' => 'Modification de la teinte du cheveux afin de les éclaircir ou de les assombrir. Suivi d\'une mise en forme des cheveux.',
            ],
            [
                'name' => 'Mèches',
                'duration' => 120,
                'price' => 110,
                'desc' => 'Décoloration ou coloration du cheveux afin de créer un contraste et un relief.',
            ],
            [
                'name' => 'Permanente',
                'duration' => 90,
                'price' => 75,
                'desc' => 'Transformation de cheveux raides en boucles tout en donnant du volume aux cheveux.',
            ],
            [
                'name' => 'Lissage brésilien',
                'duration' => 120,
                'price' => 110,
                'desc' => 'Soin capillaire profond à base de kératine qui détent et nourrit les cheveux tout en éliminant les frisottis. Le lissage dure entre 2 à 4 mois.',
            ],
        ];

        Client::factory(10)->create();

        foreach ($services as $service) {
            Service::create($service);
        }

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
