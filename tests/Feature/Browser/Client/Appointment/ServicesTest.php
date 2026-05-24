<?php

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows selected service in fixed bar', function () {
    $service = Service::factory()->create();

    $page = visit(route('appointment'));

    $page->click('Sélectionner')
        ->assertSee('1 prestation sélectionnée ('.$service->durationFormat($service->duration).' / '.$service->price.'€)');
});

it('unselects a service to reset the fixed bar', function () {
    $service = Service::factory()->create();

    $page = visit(route('appointment'));

    $page->click('Sélectionner')
        ->assertSee('1 prestation sélectionnée ('.$service->durationFormat($service->duration).' / '.$service->price.'€)')
        ->click('.service-remove-btn')
        ->assertSee('Aucune prestation sélectionnée');
});

it('redirects to second step after clicking on continue', function () {
    Service::factory()->create();

    $page = visit(route('appointment'));

    $page->click('Sélectionner')
        ->assertVisible('Continuer')
        ->click('Continuer')
        ->assertUrlIs(route('appointment2'));
});
