<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.client.home')->name('home');
Route::view('about', 'pages.client.about')->name('about');
Route::view('/price', 'pages.client.prices')->name('price');
Route::view('/gallery', 'pages.client.gallery')->name('gallery');
Route::view('/contact', 'pages.client.contact')->name('contact');
Route::view('/appointment', 'pages.client.appointment')->name('appointment');

Route::get('/prices', [ServiceController::class, 'index'])->name('prices');

