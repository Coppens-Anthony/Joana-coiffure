<?php

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows an available slot for the date selected', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 2));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->post(route('appointment'), ['services' => $selectedIds]);

    $date = today()->addDays(3)->format('Y-m-d');

    $page = visit(route('appointment2', ['date' => $date]));

    $page->assertVisible('button[type="submit"]:has-text("10:00")');
});

it('redirects to last step after clicking on a slot', function () {
    $services = Service::factory(10)->create();
    $selectedServices = $services->random(rand(1, 2));
    $selectedIds = $selectedServices->pluck('id')->toArray();

    $this->post(route('appointment'), ['services' => $selectedIds]);

    $date = today()->addDays(3)->format('Y-m-d');

    $page = visit(route('appointment2', ['date' => $date]));

    $page->click('button[type="submit"]:has-text("10:00")')
        ->assertUrlIs(route('appointment3'));
});
