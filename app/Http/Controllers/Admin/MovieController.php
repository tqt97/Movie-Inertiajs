<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/Movie/Index', [
            'movies' => Movie::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->when(Request::has('column'), function ($query) {
                    $query->orderBy(Request::input('column'), Request::input('direction'));
                })
                ->paginate($perPage)
                ->withQueryString(),
            'filters' => Request::only(['search', 'perPage', 'column', 'direction'])
        ]);
    }
    public function store()
    {
        if (!Request::input('movie_id')) {
            return Redirect::back()->dangerBanner('Movie ID is empty. Please enter a valid Movie ID');
        }
        if (!is_numeric(Request::input('movie_id'))) {
            return Redirect::back()->dangerBanner('Movie ID is a number. Please try again');
        }
        $movie = movie::where('tmdb_id', Request::input('movie_id'))->exists();
        if ($movie) {
            return Redirect::back()->dangerBanner('Movie already exists');
        }
        $url = config('services.tmdb.endpoint') . '/movie/' . Request::input('movie_id') . '?api_key=' . config('services.tmdb.secret') . '&language=en-US';
        $apiMovie = Http::asJson()->get($url);

        if ($apiMovie->successful()) {
            $created_movie = Movie::create([
                'tmdb_id' => $apiMovie['id'],
                'title' => $apiMovie['title'],
                'runtime' => $apiMovie['runtime'],
                'rating' => $apiMovie['vote_average'],
                'release_date' => $apiMovie['release_date'],
                'lang' => $apiMovie['original_language'],
                'video_format' => 'HD',
                'is_public' => false,
                'overview' => $apiMovie['overview'],
                'poster_path' => $apiMovie['poster_path'],
                'backdrop_path' => $apiMovie['backdrop_path']
            ]);
            $tmdb_genres = $apiMovie['genres'];
            $tmdb_genres_ids = collect($tmdb_genres)->pluck('id');
            $genres = Genre::whereIn('tmdb_id', $tmdb_genres_ids)->get();
            $created_movie->genres()->attach($genres);

            return Redirect::back()->banner('Movie added successfully');
        } else {
            return Redirect::back()->dangerBanner('Api not exists. Please try again');
        }
    }
    public function edit(Movie $movie)
    {
        return Inertia::render('Admin/Movie/Edit', [
            'movie' => $movie
        ]);
    }

    public function update(Movie $movie)
    {
        $movie->update(Request::validate([
            'title' => 'required',
            'runtime' => 'required',
            'lang' => 'required',
            'video_format' => 'required',
            'rating' => 'required',
            'poster_path' => 'required',
            'backdrop_path' => 'required',
            'overview' => 'required',
            'is_public' => 'required'
        ]));
        return Redirect::route('admin.movies.index')->banner('Movie updated successfully');
    }
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return Redirect::back()->banner('Movie deleted successfully');
    }
}
