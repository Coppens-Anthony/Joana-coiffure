<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns appointments as events', function () {
    $client = Client::factory()->create();

    Appointment::create([
        'client_id' => $client->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    $page = Livewire::test('pages::admin.agenda');

    expect($page->events)
        ->toHaveCount(1)
        ->first()->toMatchArray([
            'title' => $client->name,
        ]);
});

it('creates an appointment', function () {
    $client = Client::factory()->create();
    $service = Service::factory()->create();

    $component = Livewire::test('modals::appointments.create', ['params' => ['date' => today()->addDays(4)->toDateString()]])
        ->set('client_id', $client->id)
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
    Appointment::factory()->create();

    $page = Livewire::test('pages::admin.agenda');

    expect($page->events)->toHaveCount(1);
});

it('deletes an appointment after confirmation', function () {
    $client = Client::factory()->create();

    $appointment = Appointment::create([
        'client_id' => $client->id,
        'message' => '',
        'start_at' => today()->setTime(10, 0),
        'end_at' => today()->setTime(11, 0),
    ]);

    $page = Livewire::test('pages::admin.agenda');
    expect($page->events)->toHaveCount(1);

    Livewire::test('modals::appointments.delete', ['model_id' => $appointment->id])
        ->set('contactClient', false)
        ->call('destroy');

    $page = Livewire::test('pages::admin.agenda');
    expect($page->events)->toHaveCount(0);
});
