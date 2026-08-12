<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to services\'page if there is no service in the session', function () {
    $user = User::factory()->create();

    $response = $this->withSession(['appointment.user_id' => $user->id])
        ->get(route('appointment3'));

    $response->assertRedirect(route('appointment2'));
});

it('verifies if there is at least one service in the session for accessing the date page', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 10));
    $selectedIds = $selectedServices->pluck('id')->toArray();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);

    $response = $this->withSession([
        'appointment.services' => [$selectedIds],
        'appointment.user_id' => $user->id
    ])->get(route('appointment3'));

    $response->assertStatus(200);
});

it('selects current day by default', function () {
    $service = Service::factory()->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);

    $response = $this->withSession([
        'appointment.services' => [$service->id],
        'appointment.user_id' => $user->id,
    ])->get(route('appointment3'));

    $response->assertViewHas('dateValue', today()->format('Y-m-d'));
});

it('returns slots for today', function () {
    $service = Service::factory()->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);
    $today = today()->format('Y-m-d');

    $response = $this->withSession([
        'appointment.services' => [$service->id],
        'appointment.user_id' => $user->id,
    ])->get(route('appointment3', ['date' => $today]));

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
