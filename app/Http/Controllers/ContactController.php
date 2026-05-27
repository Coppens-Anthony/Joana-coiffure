<?php

namespace App\Http\Controllers;

use App\Mails\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'telephone' => 'required',
            'message' => 'required|max:255',
        ]);

        Mail::to(config('mail.to.address'))->send(
            new ContactForm($validated)
        );

        return redirect(route('contact'))->with('success', true);
    }
}
