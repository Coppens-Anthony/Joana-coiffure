<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns appointments as events', function () {
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

    $page = Livewire::test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);

    expect($page->events)
        ->toHaveCount(1)
        ->first()->toMatchArray([
            'title' => $client->name,
        ]);
});

it('creates an appointment', function () {
    $client = Client::factory()->create();
    $service = Service::factory()->create();
    $user = User::factory()->create();
    $admin = User::factory()->create(['isAdmin' => true]);

    $component = Livewire::actingAs($admin)
        ->test('modals::appointments.create_edit', ['model_id' => null, 'params' => ['date' => today()->addDays(4)->toDateString()]])
        ->set('client_id', $client->id)
        ->set('selected_user_id', $user->id)
        ->set('services_id', [$service->id]);

    $slot = array_key_first($component->appointmentSlots);
    $period = explode('-', $slot);

    $component
        ->set('hour', $slot)
        ->call('store');

    $this->assertDatabaseHas('appointments', [
        'client_id' => $client->id,
        'start_at' => today()->addDays(4)->format('Y-m-d').' '.$period[0].':00',
        'end_at' => today()->addDays(4)->format('Y-m-d').' '.$period[1].':00',
    ]);
});

it('shows appointments of the day', function () {
    Client::factory()->create();
    $user = User::factory()->create(['isAdmin' => true]);
    Appointment::factory()->create([
        'user_id' => $user->id,
        'start_at' => today()->setTime(10, 0),
    ]);

    $page = Livewire::test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);

    expect($page->events)->toHaveCount(1);
});

it('deletes an appointment after confirmation', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create(['isAdmin' => true]);

    $appointment = Appointment::create([
        'uuid' => Str::uuid(),
        'client_id' => $client->id,
        'user_id' => $user->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    $page = Livewire::actingAs($user)->test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);
    expect($page->events)->toHaveCount(1);

    Livewire::actingAs($user)->test('modals::appointments.delete', ['model_id' => $appointment->uuid])
        ->set('contactClient', false)
        ->call('destroy');

    $page = Livewire::actingAs($user)->test('admin.members.calendar', ['calendar_name' => 'calendar', 'user' => $user]);
    expect($page->events)->toHaveCount(0);
});
