<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceController;

Route::view('/', 'pages.client.home')->name('home');
Route::view('/a-propos', 'pages.client.about')->name('about');
Route::view('/contact', 'pages.client.contact')->name('contact');
Route::view('/rendez-vous/date', 'pages.client.appointment.appointment2')->name('appointment2');
Route::view('/rendez-vous/confirmation', 'pages.client.appointment.appointment3')->name('appointment3');
Route::view('/mentions-legales', 'pages.client.legal_notice')->name('notice');

Route::get('/galerie', [GalleryController::class, 'index'])->name('gallery');

Route::get('/prestations', [ServiceController::class, 'index'])->name('prices');
Route::get('/rendez-vous', [AppointmentController::class, 'index'])->name('appointment');


Route::view('/login', 'pages.admin.login')->name('login')->middleware('guest');
