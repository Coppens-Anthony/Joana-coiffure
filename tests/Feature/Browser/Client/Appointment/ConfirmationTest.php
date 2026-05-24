<?php

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to last step after clicking on a slot', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 2));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->post(route('appointment'), ['services' => $selectedIds]);

    $date = today()->addDays(3)->format('Y-m-d');

    $this->post(route('appointment2.store', ['date' => $date, 'slot' => '10:00']));

    $page = visit(route('appointment3'));

    $page->fill('name', 'John Doe')
        ->fill('email', 'john@doe.com')
        ->fill('telephone', '0123 45 67 89')
        ->fill('message', 'Test message')
        ->click('Envoyer');

    $appointment = Appointment::latest()->first();

    $page->assertUrlIs(route('thanks', $appointment->id));
});
