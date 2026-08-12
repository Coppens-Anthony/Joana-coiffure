<?php

use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('verifies if there is at least one service in the session, a user id, a date and a slot for accessing the confirmation page', function () {
    $services = Service::factory(10)->create();
    $user = User::factory()->create();
    $selectedServices = $services->random(rand(1, 10));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $response = $this->withSession([
        'appointment.services' => $selectedIds,
        'appointment.user_id' => $user->id,
        'appointment.date' => today()->addDays(7)->format('Y-m-d'),
        'appointment.slot' => '10:00',
    ])->get(route('appointment4'));

    $response->assertStatus(200);
});

it('redirects to services\'page if there is no service in the session', function () {
    $response = $this->get(route('appointment4'));

    $response->assertRedirect(route('appointment3'));
});

it('redirects if no date in session for confirmation page', function () {
    $services = Service::factory(10)->create();
    $selectedIds = $services->random(rand(1, 10))->pluck('id')->toArray();
    $user = User::factory()->create();

    $response = $this->withSession([
        'appointment.services' => $selectedIds,
        'appointment.user_id' => $user->id,
        'appointment.slot' => '10:00',
    ])->get(route('appointment4'));

    $response->assertRedirect(route('appointment3'));
});

it('redirects if no slot in session for confirmation page', function () {
    $services = Service::factory(10)->create();
    $selectedIds = $services->random(rand(1, 10))->pluck('id')->toArray();
    $user = User::factory()->create();

    $response = $this->withSession([
        'appointment.services' => $selectedIds,
        'appointment.date' => today()->addDays(7)->format('Y-m-d'),
        'appointment.user_id' => $user->id,
    ])->get(route('appointment4'));

    $response->assertRedirect(route('appointment3'));
});

it('verifies if the appointment is well created after the form submit', function () {
    $services = Service::factory(10)->create();
    $selectedIds = $services->random(rand(1, 10))->pluck('id')->toArray();
    $date = today()->addDays(7)->format('Y-m-d');
    $slot = '10:00';
    $user = User::factory()->create();

    $this->withSession([
        'appointment.services' => $selectedIds,
        'appointment.user_id' => $user->id,
        'appointment.date' => $date,
        'appointment.slot' => $slot,
    ])->post(route('appointment4.store'), [
        'name' => 'John Doe',
        'email' => 'joana@doe.com',
        'telephone' => '0466486777',
        'message' => null,
    ]);

    $this->assertDatabaseCount('appointments', 1);
    $this->assertDatabaseCount('appointment_service', count($selectedIds));
    $this->assertDatabaseCount('clients', 1);
});

it('chooses existing client if the email already exists in table', function () {
    Client::create([
        'name' => 'Test',
        'email' => 'test@test.com',
        'telephone' => '0123456789',
    ]);

    $services = Service::factory(10)->create();
    $selectedIds = $services->random(rand(1, 10))->pluck('id')->toArray();
    $date = today()->addDays(7)->format('Y-m-d');
    $slot = '10:00';
    $user = User::factory()->create();

    $this->withSession([
        'appointment.services' => $selectedIds,
        'appointment.user_id' => $user->id,
        'appointment.date' => $date,
        'appointment.slot' => $slot,
    ])->post(route('appointment4.store'), [
        'name' => 'John Doe',
        'email' => 'test@test.com',
        'telephone' => '0466486777',
        'message' => null,
    ]);

    $this->assertDatabaseCount('clients', 1);
    $this->assertDatabaseHas('appointments', [
        'client_id' => Client::where('email', 'test@test.com')->first()->id,
    ]);
});
