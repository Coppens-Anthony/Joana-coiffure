<?php

use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows every pictures in the gallery\'s page', function () {
    $photos = Photo::factory(10)->create();

    $response = $this->get(route('gallery'));

    $response->assertStatus(200);

    foreach ($photos as $photo) {
        $response->assertSee($photo->picture);
    }
});
