<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $man = Service::where('name', 'Homme')->first();
        $meches = Service::where('name', 'Mèches')->first();
        $permanente = Service::where('name', 'Permanente')->first();

        return view('pages.client.home', compact('man', 'meches', 'permanente'));
    }
}
