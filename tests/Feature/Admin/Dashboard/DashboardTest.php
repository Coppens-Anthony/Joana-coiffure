<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('shows every appointment of the day', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();

    Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    $page = Livewire::actingAs($user)->test('pages::admin.dashboard');

    expect($page->appointments)->toHaveCount(1);
});

it('does not show appointments from other days', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();

    Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->addDays(3)->setTime(10, 0),
        'end_at' => today()->addDays(3)->setTime(11, 0),
    ]);

    $page = Livewire::actingAs($user)->test('pages::admin.dashboard');

    expect($page->appointments)->toHaveCount(0);
});

it('deletes an appointment after confirmation', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();

    $appointment = Appointment::create([
        'uuid' => Str::uuid(),
        'user_id' => $user->id,
        'client_id' => $client->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    $page = Livewire::actingAs($user)->test('pages::admin.dashboard');
    expect($page->appointments)->toHaveCount(1);

    Livewire::actingAs($user)->test('modals::appointments.delete', ['model_id' => $appointment->uuid])
        ->set('contactClient', false)
        ->call('destroy');

    $page = Livewire::actingAs($user)->test('pages::admin.dashboard');
    expect($page->appointments)->toHaveCount(0);
});
