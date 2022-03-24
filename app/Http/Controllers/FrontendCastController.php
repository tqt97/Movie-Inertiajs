<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use Inertia\Inertia;

class FrontendCastController extends Controller
{
    public function index()
    {
        return Inertia::render('Frontend/Cast/Index', [
            'casts' => Cast::orderBy('updated_at', 'desc')->paginate(12)
        ]);
    }

    public function show(Cast $cast)
    {
        $movies = $cast->movies;

        return Inertia::render('Frontend/Cast/Show', [
            'cast' => $cast,
            'movies' => $movies,
        ]);
    }
}
