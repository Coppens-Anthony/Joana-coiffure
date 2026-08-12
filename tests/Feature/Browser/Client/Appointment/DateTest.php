<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows an available slot for the date selected', function () {
    $services = Service::factory(10)->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);
    $selectedServices = $services->random(rand(1, 2));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->post(route('appointment'), ['services' => $selectedIds]);
    $this->post(route('appointment2.store'), ['user_id' => $user->id]);

    $date = today()->addDays(3)->format('Y-m-d');

    $page = visit(route('appointment3', ['date' => $date]));

    $page->assertVisible('button[type="submit"]:has-text("10:00")');
});

it('redirects to last step after clicking on a slot', function () {
    $services = Service::factory(10)->create();
    $user = User::factory()->create();
    User::factory()->create(['isAdmin' => true]);
    $selectedServices = $services->random(rand(1, 2));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->post(route('appointment'), ['services' => $selectedIds]);
    $this->post(route('appointment2'), ['user_id' => $user->id]);
    $date = today()->addDays(3)->format('Y-m-d');

    $page = visit(route('appointment3', ['date' => $date]));

    $page->click('button[type="submit"]:has-text("10:00")')
        ->assertUrlIs(route('appointment4'));
});
