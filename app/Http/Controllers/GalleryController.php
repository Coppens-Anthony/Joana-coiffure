<?php

namespace App\Http\Controllers;
use App\Models\Photo;

class GalleryController
{
    public function index()
    {
        $photos = Photo::orderByDesc('created_at')->get();

        return view('pages.client.gallery', compact('photos'));
    }
}
