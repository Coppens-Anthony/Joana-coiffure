<?php

use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

it('shows every photos in the index page', function () {
    Photo::factory(10)->create();

    $page = Livewire::test('admin.database.photos');

    expect($page->photos)->toHaveCount(10);
});

it('creates a new photo', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');

    Livewire::test('modals::pictures.create')
        ->set('pictures', $file)
        ->call('store');

    expect(Photo::count())->toBe(1);
});

it('deletes a photo', function () {
    $photo = Photo::factory()->create();

    expect(Photo::count())->toBe(1);

    Livewire::test('modals::pictures.delete', ['model_id' => $photo->id])
        ->call('destroy');

    expect(Photo::count())->toBe(0);
});
