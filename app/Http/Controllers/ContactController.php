<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'telephone' => 'required',
            'message' => 'required|max:255',
        ]);

        return redirect(route('contact'))->with('success', true);
    }
}
