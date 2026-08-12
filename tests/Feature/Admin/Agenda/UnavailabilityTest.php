<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Unavailability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('returns unavailabilities as events', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);

    Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    Unavailability::factory()->create();

    $page = Livewire::test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);

    expect($page->events)
        ->toHaveCount(2);
});

it('returns an unavailability with "Journée off" for title because all day is off', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);

    Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    Unavailability::factory()->create([
        'start_at' => today()->setTime(9, 0),
        'end_at' => today()->setTime(18, 0),
    ]);

    $page = Livewire::test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);

    expect($page->events)
        ->toHaveCount(2)
        ->last()->toMatchArray([
            'title' => 'Journée indisponible',
        ]);
});
it('returns an unavailability with "Créneau off" for title because a part of the day is off', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);

    Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    Unavailability::factory()->create([
        'start_at' => today()->setTime(9, 0),
        'end_at' => today()->setTime(12, 0),
    ]);

    $page = Livewire::test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);

    expect($page->events)
        ->toHaveCount(2)
        ->last()->toMatchArray([
            'title' => 'Créneau indisponible',
        ]);
});

it('returns an unavailability with "Période off" for title because at least 2 days are off', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);

    Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    Unavailability::factory()->create([
        'start_at' => today()->setTime(9, 0),
        'end_at' => today()->addDays(3)->setTime(18, 0),
    ]);

    $page = Livewire::test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);

    expect($page->events)
        ->toHaveCount(2)
        ->last()->toMatchArray([
            'title' => 'Période indisponible',
        ]);
});
