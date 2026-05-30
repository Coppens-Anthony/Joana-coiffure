<?php

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows home page', function () {
    Service::factory()->create([
        'name' => 'Homme',
    ]);
    Service::factory()->create([
        'name' => 'Mèches',
    ]);
    Service::factory()->create([
        'name' => 'Permanente',
    ]);

    $page = visit(route('home'));

    $page->assertSee('Joana-Coiffure');
    $page->assertSee('Coiffeuse & visagiste indépendante à Orp-Jauche');
});

it('redirects to contact page', function (int $index) {
    Service::factory()->create([
        'name' => 'Homme',
    ]);
    Service::factory()->create([
        'name' => 'Mèches',
    ]);
    Service::factory()->create([
        'name' => 'Permanente',
    ]);

    $page = visit(route('home'));

    $page->click('a[title="Vers la page de contact"] >> nth='.$index)
        ->assertUrlIs(route('contact'));
})->with([0, 1, 2, 3]);
