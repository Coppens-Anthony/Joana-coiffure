<?php

use App\Livewire\Members\CompleteInvitation;
use App\Models\User;

Route::prefix('admin')->group(function () {

    Route::view('/', 'pages.admin.login')
        ->name('login')->middleware('guest');

    Route::livewire('/dashboard', 'pages::admin.⚡dashboard')
        ->name('dashboard')->middleware('auth');

    Route::livewire('/agenda', 'pages::admin.⚡agenda')
        ->name('agenda')->middleware('auth');

    Route::livewire('/clients', 'pages::admin.clients.⚡index')
        ->name('clients.index')->middleware('auth');
    Route::livewire('/clients/{client}', 'pages::admin.clients.⚡show')
        ->name('clients.⚡show')->middleware('auth');

    Route::livewire('/statistiques', 'pages::admin.⚡statistics')
        ->name('statistics')->middleware('auth');

    Route::livewire('/prestations', 'pages::admin.database.services.⚡index')
        ->name('database.services')->middleware('auth');
    Route::livewire('/galerie', 'pages::admin.database.photos.⚡index')
        ->name('database.gallery')->middleware('auth')->can('view', User::class);

    Route::livewire('/congés-récurrents', 'pages::admin.⚡recurring_unavailabilities')
        ->name('recurring_unavailabilities')->middleware('auth');

    Route::livewire('/membres', 'pages::admin.members.⚡index')
        ->name('members.index')->middleware('auth');
    Route::livewire('/membres/{user}', 'pages::admin.members.⚡show')
        ->name('members.show')->middleware('auth');

    Route::livewire('/profil', 'pages::admin.⚡profile')
        ->name('profile')->middleware('auth');
});
