<?php

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to services\'page if there is no service in the session', function () {
    $response = $this->get(route('appointment2'));

    $response->assertRedirect(route('appointment'));
});

it('verifies if there is at least one service in the session for accessing the date page', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 10));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $response = $this->withSession(['appointment.services' => [$selectedIds]])
        ->get(route('appointment2'));

    $response->assertStatus(200);
});

it('selects current day by default', function () {
    $service = Service::factory()->create();

    $response = $this->withSession(['appointment.services' => [$service->id]])
        ->get(route('appointment2'));

    $response->assertViewHas('dateValue', today()->format('Y-m-d'));
});

it('returns slots for today', function () {
    $service = Service::factory()->create();
    $today = today()->format('Y-m-d');

    $response = $this->withSession(['appointment.services' => [$service->id]])
        ->get(route('appointment2', ['date' => $today]));

    $response->assertViewHas('slots');
    $response->assertViewHas('dateValue', $today);
});

it('verifies if a user can directly go on this page if there are services in the session', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 10));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->withSession(['appointment.services' => $selectedIds])
        ->get(route('home'));

    $response = $this->get(route('appointment2'));

    $response->assertStatus(200);
});
