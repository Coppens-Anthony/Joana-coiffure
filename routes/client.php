<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;

Route::view('/', 'pages.client.home')->name('home');
Route::view('about', 'pages.client.about')->name('about');
Route::view('/price', 'pages.client.prices')->name('price');
Route::view('/gallery', 'pages.client.gallery')->name('gallery');
Route::view('/contact', 'pages.client.contact')->name('contact');
Route::view('/appointment/date', 'pages.client.appointment.appointment2')->name('appointment2');
Route::view('/appointment/confirmation', 'pages.client.appointment.appointment3')->name('appointment3');

Route::get('/prices', [ServiceController::class, 'index'])->name('prices');
Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');


Route::view('/login', 'pages.admin.login')->name('login')->middleware('guest');
