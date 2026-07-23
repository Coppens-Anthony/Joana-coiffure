<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TestController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/a-propos', 'pages.client.about')->name('about');
Route::view('/mentions-legales', 'pages.client.legal_notice')->name('notice');

Route::get('/confirmation-rendez-vous/{appointment}', [ConfirmationController::class, 'show'])->name('thanks');

Route::view('/contact', 'pages.client.contact')->name('contact');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/galerie', [GalleryController::class, 'index'])->name('gallery');

Route::get('/prestations', [ServiceController::class, 'index'])->name('prices');


Route::get('/rendez-vous/prestations', [AppointmentController::class, 'services'])->name('appointment');
Route::post('/rendez-vous/prestations', [AppointmentController::class, 'servicesStore'])->name('appointment.store');

Route::get('/rendez-vous/date', [AppointmentController::class, 'date'])->name('appointment2');
Route::post('/rendez-vous/date', [AppointmentController::class, 'dateStore'])->name('appointment2.store');

Route::get('/rendez-vous/confirmation', [AppointmentController::class, 'confirmation'])->name('appointment3');
Route::post('/rendez-vous/confirmation', [AppointmentController::class, 'confirmationStore'])->name('appointment3.store');


Route::get('/rendez-vous/{appointment}/annulation', [AppointmentController::class, 'appointment_cancel_view'])->name('appointment_cancel.view');
Route::post('/rendez-vous/{appointment}/annulation', [AppointmentController::class, 'appointment_cancel'])->name('appointment_cancel');


Route::view('/login', 'pages.admin.login')->name('login')->middleware('guest');
Route::view('/register', 'pages.admin.login')->name('login')->middleware('guest');
