<?php

namespace App\Http\Controllers;
use App\Models\Photo;

class GalleryController
{
    public function index()
    {
        $photos = Photo::orderByDesc('position')->get()->values();

        return view('pages.client.gallery', compact('photos'));
    }
}
