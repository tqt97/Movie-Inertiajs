<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Inertia\Inertia;

class FrontendGenreController extends Controller
{
    public function show(Genre $genre)
    {
        return Inertia::render('Frontend/Genre/Index', [
            'genre' => $genre,
            'movies' => $genre->movies()->paginate(12)
        ]);
    }
}
