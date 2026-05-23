<?php

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows every services in the appointment\' page', function () {
    $services = Service::factory(10)->create();

    $response = $this->get(route('appointment'));

    $response->assertStatus(200);

    foreach ($services as $service) {
        $response->assertSee($service->name);
        $response->assertSee($service->durationFormat($service->duration));
        $response->assertSee($service->price);
        $response->assertSee($service->desc);
    }
});

it('puts selected services in the session', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 10));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $response = $this->post(route('appointment'), ['services' => $selectedIds]);

    $response->assertSessionHas('appointment.services', $selectedIds);
});

it('verifies if the selected services are still in the session after a page change', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 10));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->post(route('appointment'), ['services' => $selectedIds]);

    $response = $this->get(route('home'));

    $response->assertSessionHas('appointment.services', $selectedIds);
});
