<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates user data', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($user);

    Livewire::test('pages::admin.profile')
        ->set('name', 'Nouveau User')
        ->set('email', $user->email)
        ->set('oldPassword', 'password')
        ->call('update')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nouveau User',
    ]);
});

it('changes password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($user);

    Livewire::test('pages::admin.profile')
        ->set('name', $user->name)
        ->set('email', $user->email)
        ->set('oldPassword', 'password')
        ->set('password', 'password10')
        ->call('update')
        ->assertHasNoErrors();

    expect(Hash::check('password10', $user->password))->toBeTrue();
});
