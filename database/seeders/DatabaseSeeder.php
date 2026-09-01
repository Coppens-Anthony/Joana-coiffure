<?php

namespace Database\Seeders;

use App\Jobs\ProcessUploadedPicture;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Client;
use App\Models\Photo;
use App\Models\RecurringUnavailability;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private array $bookedSlots = [];

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Salon',
            'email' => 'john@doe.com',
            'password' => Hash::make('password'),
            'isAdmin' => true,
        ]);

        $users = [
            ['name' => 'Anthony Coppens', 'email' => 'anthonycoppens04@gmail.com'],
            ['name' => 'Maud Wera', 'email' => 'maud.wera@hepl.be'],
            ['name' => 'François Parmentier', 'email' => 'francois.parmentier@hepl.be'],
            ['name' => 'Julien Mertens', 'email' => 'julien@mertens.com'],
            ['name' => 'Camille Dubois', 'email' => 'camille@dubois.com'],
            ['name' => 'Nicolas Vanden', 'email' => 'nicolas@vanden.com'],
            ['name' => 'Elise Renard', 'email' => 'elise@renard.com'],
            ['name' => 'Sophie Lambert', 'email' => 'sophie@lambert.com'],
        ];

        $avatars = ['user_1.jpg', 'user_2.jpg', 'user_3.jpg', 'user_4.jpg', 'user_5.jpg', 'user_6.jpg', 'user_7.jpg', 'user_8.jpg'];

        $processedAvatars = [];

        foreach ($avatars as $avatar) {

            $newName = uniqid().'.jpg';

            $sourcePath = public_path("assets/img/originals/$avatar");

            $relativePath = config('pictures.original_path').'/'.$newName;
            $disk = config('filesystems.default');

            Storage::disk($disk)->put(
                $relativePath,
                file_get_contents($sourcePath)
            );

            ProcessUploadedPicture::dispatchSync($relativePath, $newName);

            $processedAvatars[] = $newName;
        }

        foreach ($users as $index => $user) {
            User::factory()->create([
                'avatar' => $processedAvatars[$index] ?? null,
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'isAdmin' => false,
            ]);
        }

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
            $disk = config('filesystems.default');

            Storage::disk($disk)->put(
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

        Client::factory(30)->create();

        $this->seedRecurringUnavailabilities();
        $this->seedAppointments();
    }

    private function seedRecurringUnavailabilities(): void
    {
        $recurring = [
            ['days_of_week' => [0, 6], 'start_time' => config('app.hours.hour_start'), 'end_time' => config('app.hours.hour_end'), 'user_id' => 1],
            ['days_of_week' => [3], 'start_time' => '12:00', 'end_time' => config('app.hours.hour_end'), 'user_id' => 2],
            ['days_of_week' => [1, 2, 4, 5], 'start_time' => '12:00', 'end_time' => '13:00', 'user_id' => 3],
        ];

        $startsOn = Carbon::now()->subMonths(3)->startOfMonth();
        $blockUntil = Carbon::now()->addMonths(2)->endOfDay();

        foreach ($recurring as $r) {
            RecurringUnavailability::create([
                'uuid' => Str::uuid(),
                'days_of_week' => $r['days_of_week'],
                'start_time' => $r['start_time'],
                'end_time' => $r['end_time'],
                'starts_on' => $startsOn,
                'ends_on' => '9999-12-31',
                'user_id' => $r['user_id'],
            ]);

            $this->blockRecurringInWindow($r['days_of_week'], $r['start_time'], $r['end_time'], $startsOn, $blockUntil);
        }
    }

    private function blockRecurringInWindow(array $daysOfWeek, string $startTime, string $endTime, Carbon $startsOn, Carbon $endWindow): void
    {
        [$startH, $startM] = explode(':', $startTime);
        [$endH, $endM] = explode(':', $endTime);

        $cursor = $startsOn->copy();

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
        $userIds = User::pluck('id')->toArray();

        $base = Carbon::now()->subMonths(18)->startOfMonth();
        $today = Carbon::now();
        $rangeInDays = $base->diffInDays($today->addMonths(2));

        $created = 0;
        $attempts = 0;
        $target = 10000;

        while ($created < $target && $attempts < 10000) {
            $attempts++;

            $start = $base->copy()
                ->addDays(rand(0, $rangeInDays))
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
                'uuid' => Str::uuid(),
                'client_id' => $clientIds[array_rand($clientIds)],
                'user_id' => $userIds[array_rand($userIds)],
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
