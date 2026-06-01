<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $man = Service::where('name', 'Homme')->first()
            ?? Service::inRandomOrder()->first();

        $meches = Service::where('name', 'Mèches')->first()
            ?? Service::inRandomOrder()->first();

        $permanente = Service::where('name', 'Permanente')->first()
            ?? Service::inRandomOrder()->first();

        return view('pages.client.home', compact('man', 'meches', 'permanente'));
    }
}
