<?php

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

    Route::livewire('/donnes/services', 'pages::admin.database.services.⚡index')
        ->name('database.services')->middleware('auth');
    Route::livewire('/donnes/photos', 'pages::admin.database.photos.⚡index')
        ->name('database.photos')->middleware('auth');

    Route::livewire('/profil', 'pages::admin.⚡profile')
        ->name('profile')->middleware('auth');
});
