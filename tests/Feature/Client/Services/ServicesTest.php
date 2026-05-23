<?php

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows every services in the services\' page', function () {
    $services = Service::factory(10)->create();

    $response = $this->get(route('prices'));

    $response->assertStatus(200);

    foreach ($services as $service) {
        $response->assertSee($service->name);
        $response->assertSee($service->durationFormat($service->duration));
        $response->assertSee($service->price);
        $response->assertSee($service->desc);
    }
});
