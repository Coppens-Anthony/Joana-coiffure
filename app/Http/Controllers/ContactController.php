<?php

namespace App\Http\Controllers;

use App\Mails\ContactForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'telephone' => 'required|regex:/^\+?[0-9\s\-\(\)]{7,20}$/',
            'message' => 'required|max:255',
        ]);

        $users = User::pluck('email', 'id');

        foreach ($users as $user) {
            Mail::to($user)->send(
                new ContactForm($validated)
            );
        }

        Mail::to(config('mail.reply_to.address'))->send(
            new ContactForm($validated)
        );

        Mail::to('joanacoiffure190@gmail.com')->send(
            new ContactForm($validated)
        );

        return redirect(route('contact'))->with('success', true);
    }
}
