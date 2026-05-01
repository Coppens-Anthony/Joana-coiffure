<?php

namespace App\Http\Controllers;

use App\Models\Service;

class AppointmentController
{
    public function index()
    {
        $services = Service::all();

        return view('pages.client.appointment.appointment', compact('services'));
    }
}
