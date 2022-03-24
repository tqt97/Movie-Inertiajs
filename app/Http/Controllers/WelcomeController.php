<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Movie;
use App\Models\TvShow;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('updated_at', 'desc')->with('genres')->take(12)->get();
        $tvShows = TvShow::withCount('seasons')->orderBy('created_at', 'desc')->take(12)->get();
        $episodes = Episode::orderBy('created_at', 'desc')->with('season')->take(12)->get();

        return Inertia::render('Welcome', [
            'movies' => $movies,
            'episodes' => $episodes,
            'tvShows' => $tvShows,
        ]);
    }
}
