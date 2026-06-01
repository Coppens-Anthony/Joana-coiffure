<?php

namespace Database\Seeders;

use App\Jobs\ProcessUploadedPicture;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Client;
use App\Models\Photo;
use App\Models\RecurringUnavailability;
use App\Models\Service;
use App\Models\Unavailability;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private array $bookedSlots = [];

    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => Hash::make('password'),
        ]);

        $photos = [
            'gallery_example2.jpg',
            'gallery_example1.jpg',
            'gallery_example3.jpg',
            'gallery_example5.jpg',
            'gallery_example4.jpg',
            'gallery_example6.jpg',
            'gallery_example7.jpg',
        ];

        foreach ($photos as $photo) {

            $newName = uniqid().'.jpg';

            $sourcePath = public_path("assets/img/originals/$photo");

            $relativePath = config('pictures.original_path').'/'.$newName;

            Storage::disk('public')->put(
                $relativePath,
                file_get_contents($sourcePath)
            );

            ProcessUploadedPicture::dispatchSync($relativePath, $newName);

            Photo::factory()->create([
                'picture' => $newName,
            ]);
        }

        $services = [
            ['name' => 'Homme', 'duration' => 20, 'price' => 15, 'desc' => 'Coupe homme aux ciseaux et/ou à la tondeuse selon le style souhaité. Travail des longueurs, des contours et finition soignée.'],
            ['name' => 'Enfant', 'duration' => 20, 'price' => 20, 'desc' => 'Coupe enfant aux ciseaux et/ou à la tondeuse, adaptée à l\'âge et à la nature du cheveu. Résultat naturel, propre et facile à entretenir avec une finition douce.'],
            ['name' => 'Coupe + brushing', 'duration' => 40, 'price' => 40, 'desc' => 'Coupe personnalisée suivie d\'un brushing adapté à votre style. Travail de la forme et du volume pour un rendu soigné et structuré.'],
            ['name' => 'Coloration + brushing', 'duration' => 90, 'price' => 75, 'desc' => 'Modification de la teinte du cheveux afin de les éclaircir ou de les assombrir. Suivi d\'une mise en forme des cheveux.'],
            ['name' => 'Mèches', 'duration' => 120, 'price' => 110, 'desc' => 'Décoloration ou coloration du cheveux afin de créer un contraste et un relief.'],
            ['name' => 'Permanente', 'duration' => 90, 'price' => 75, 'desc' => 'Transformation de cheveux raides en boucles tout en donnant du volume aux cheveux.'],
            ['name' => 'Lissage brésilien', 'duration' => 120, 'price' => 110, 'desc' => 'Soin capillaire profond à base de kératine qui détent et nourrit les cheveux tout en éliminant les frisottis. Le lissage dure entre 2 à 4 mois.'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }


        Client::factory(10)->create();

        $this->seedUnavailabilities();
        $this->seedRecurringUnavailabilities();
        $this->seedAppointments();
    }

    private function seedUnavailabilities(): void
    {
        $base = Carbon::now()->subMonth()->startOfMonth();

        $cases = [
            ['type' => 'full_day', 'date' => $base->copy()->addDays(3)],
            ['type' => 'full_day', 'date' => $base->copy()->addDays(10)],
            ['type' => 'full_day', 'date' => $base->copy()->addDays(45)],

            ['type' => 'slot', 'date' => $base->copy()->addDays(5), 'from' => '10:00', 'to' => '12:00'],
            ['type' => 'slot', 'date' => $base->copy()->addDays(7), 'from' => '14:00', 'to' => '16:30'],
            ['type' => 'slot', 'date' => $base->copy()->addDays(14), 'from' => '09:00', 'to' => '11:00'],
            ['type' => 'slot', 'date' => $base->copy()->addDays(46), 'from' => '13:00', 'to' => '15:00'],
            ['type' => 'slot', 'date' => $base->copy()->addDays(70), 'from' => '09:00', 'to' => '10:30'],

            ['type' => 'period', 'from' => $base->copy()->addDays(18), 'to' => $base->copy()->addDays(21)],
            ['type' => 'period', 'from' => $base->copy()->addDays(60), 'to' => $base->copy()->addDays(64)],
        ];

        foreach ($cases as $case) {
            if ($case['type'] === 'full_day') {
                $start = $case['date']->copy()->setTime(9, 0);
                $end = $case['date']->copy()->setTime(18, 0);
            } elseif ($case['type'] === 'slot') {
                [$fromH, $fromM] = explode(':', $case['from']);
                [$toH, $toM] = explode(':', $case['to']);
                $start = $case['date']->copy()->setTime((int) $fromH, (int) $fromM);
                $end = $case['date']->copy()->setTime((int) $toH, (int) $toM);
            } else {
                $start = $case['from']->copy()->setTime(9, 0);
                $end = $case['to']->copy()->setTime(18, 0);
            }

            if ($this->overlaps($start, $end)) {
                continue;
            }

            Unavailability::create([
                'start_at' => $start,
                'end_at' => $end,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->bookSlot($start, $end);
        }
    }

    private function seedRecurringUnavailabilities(): void
    {
        $recurring = [
            ['days_of_week' => [0, 6], 'start_time' => '09:00', 'end_time' => '18:00'],
            ['days_of_week' => [3], 'start_time' => '12:00', 'end_time' => '18:00'],
            ['days_of_week' => [1, 2, 4, 5], 'start_time' => '12:00', 'end_time' => '13:00'],
        ];

        $startsOn = Carbon::now()->subMonth()->startOfMonth();

        foreach ($recurring as $r) {
            RecurringUnavailability::create([
                'days_of_week' => $r['days_of_week'],
                'start_time' => $r['start_time'],
                'end_time' => $r['end_time'],
                'starts_on' => $startsOn,
            ]);

            $this->blockRecurringInWindow($r['days_of_week'], $r['start_time'], $r['end_time'], $startsOn);
        }
    }

    private function blockRecurringInWindow(array $daysOfWeek, string $startTime, string $endTime, Carbon $startsOn): void
    {
        [$startH, $startM] = explode(':', $startTime);
        [$endH, $endM] = explode(':', $endTime);

        $cursor = $startsOn->copy();
        $endWindow = $startsOn->copy()->addDays(89);

        while ($cursor->lte($endWindow)) {
            if (in_array($cursor->dayOfWeek, $daysOfWeek)) {
                $start = $cursor->copy()->setTime((int) $startH, (int) $startM);
                $end = $cursor->copy()->setTime((int) $endH, (int) $endM);
                $this->bookSlot($start, $end);
            }
            $cursor->addDay();
        }
    }

    private function seedAppointments(): void
    {
        $services = Service::all()->keyBy('id');
        $serviceIds = $services->keys()->toArray();
        $clientIds = Client::pluck('id')->toArray();
        $base = Carbon::now()->subMonth()->startOfMonth();
        $created = 0;
        $attempts = 0;
        $target = 60;

        while ($created < $target && $attempts < 1000) {
            $attempts++;

            $start = $base->copy()
                ->addDays(rand(0, 89))
                ->setTime(rand(9, 17), rand(0, 1) * 30);

            $nbServices = rand(1, 2);
            $pickedIds = [];
            $totalMinutes = 0;

            for ($i = 0; $i < $nbServices; $i++) {
                $sid = $serviceIds[array_rand($serviceIds)];
                $pickedIds[] = $sid;
                $totalMinutes += $services[$sid]->duration;
            }

            $end = $start->copy()->addMinutes($totalMinutes);

            if ($end->hour > 18 || ($end->hour === 18 && $end->minute > 0)) {
                continue;
            }

            if ($this->overlaps($start, $end)) {
                continue;
            }

            $appointment = Appointment::create([
                'client_id' => $clientIds[array_rand($clientIds)],
                'message' => fake()->sentence(),
                'start_at' => $start,
                'end_at' => $end,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($pickedIds as $sid) {
                AppointmentService::create([
                    'appointment_id' => $appointment->id,
                    'service_id' => $sid,
                ]);
            }

            $this->bookSlot($start, $end);
            $created++;
        }

        if ($created < $target) {
            $this->command->warn("Seeder : seulement {$created}/{$target} RDV créés (pas assez de créneaux libres).");
        }
    }

    private function bookSlot(Carbon $start, Carbon $end): void
    {
        $this->bookedSlots[] = [$start->timestamp, $end->timestamp];
    }

    private function overlaps(Carbon $start, Carbon $end): bool
    {
        $s = $start->timestamp;
        $e = $end->timestamp;

        foreach ($this->bookedSlots as [$bs, $be]) {
            if ($s < $be && $e > $bs) {
                return true;
            }
        }

        return false;
    }
}
