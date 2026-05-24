<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('connects a user to the admin part', function () {
    User::factory()->create([
        'email' => 'john@doe.com',
        'password' => bcrypt('password'),
    ]);

    $page = visit(route('login'));

    $page->assertSee('Se connecter')
        ->fill('email', 'john@doe.com')
        ->fill('password', 'password')
        ->click('button[type="submit"]')
        ->assertUrlIs(route('dashboard'));

    $this->assertAuthenticated();
});

it('shows error if the credentials are not goods', function () {
    User::factory()->create([
        'email' => 'john@doe.com',
        'password' => bcrypt('password'),
    ]);

    $page = visit(route('login'));

    $page->assertSee('Se connecter')
        ->fill('email', 'john@doe.com')
        ->fill('password', 'wrong_password')
        ->click('button[type="submit"]')
        ->assertVisible('small.text-error');
});
