<?php

namespace App\Http\Controllers;
use App\Models\Appointment;

class ConfirmationController
{
    public function show(Appointment $appointment)
    {
        if (session('confirmed_appointment_id') !== $appointment->id) {
            return redirect(route('home'));
        }

        session()->forget('confirmed_appointment_id');
        
        return view('pages.client.thanks', compact('appointment'));
    }
}
