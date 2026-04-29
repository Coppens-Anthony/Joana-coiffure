<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController
{
    public function index()
    {
        $services = Service::all();
        return view('pages.client.prices', compact('services'));
    }
}
