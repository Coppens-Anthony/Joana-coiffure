<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows every services in the index page', function () {
    Service::factory(10)->create();
    $user = User::factory()->create(['isAdmin' => true]);

    $page = Livewire::actingAs($user)->test('admin.database.services');

    expect($page->services)->toHaveCount(10);
});

it('creates a new service', function () {
    Livewire::test('modals::services.create_edit', ['model_id' => null, 'params' => null])
        ->set('name', 'Test')
        ->set('duration', 50)
        ->set('price', 35)
        ->set('desc', 'Ceci est une description de test')
        ->call('store');

    expect(Service::count())->toBe(1);
});

it('updates a service', function () {
    $service = Service::factory()->create();

    Livewire::test('modals::services.create_edit', ['model_id' => $service->id, 'params' => null])
        ->assertSet('name', $service->name)
        ->assertSet('duration', $service->duration)
        ->assertSet('price', $service->price)
        ->assertSet('desc', $service->desc)
        ->set('name', 'Nom modifié')
        ->call('update');

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'name' => 'Nom modifié',
    ]);
});

it('deletes a service', function () {
    $service = Service::factory()->create();

    expect(Service::count())->toBe(1);

    Livewire::test('modals::services.delete', ['model_id' => $service->id])
        ->call('destroy');

    expect(Service::count())->toBe(0);
});
