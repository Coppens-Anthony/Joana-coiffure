<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceController;

Route::view('/', 'pages.client.home')->name('home');
Route::view('about', 'pages.client.about')->name('about');
Route::view('/price', 'pages.client.prices')->name('price');
Route::view('/contact', 'pages.client.contact')->name('contact');
Route::view('/appointment/date', 'pages.client.appointment.appointment2')->name('appointment2');
Route::view('/appointment/confirmation', 'pages.client.appointment.appointment3')->name('appointment3');
Route::view('/legal-notices', 'pages.client.legal_notice')->name('notice');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/prices', [ServiceController::class, 'index'])->name('prices');
Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');


Route::view('/login', 'pages.admin.login')->name('login')->middleware('guest');
