<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class GenreController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/Genre/Index', [
            'genres' => Genre::query()
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

    public function create()
    {
        return Inertia::render('Admin/Genre/Create');
    }

    public function store()
    {
        $api_url = Http::get(config('services.tmdb.endpoint') . 'genre/movie/list?api_key=' . config('services.tmdb.secret') . '&language=en-US');
        if ($api_url->successful()) {
            $tmdb_genres = $api_url->json();
            foreach ($tmdb_genres as $tmdb_genre) {
                foreach ($tmdb_genre as $tgenre) {
                    $result = Genre::where('tmdb_id', $tgenre['id'])->first();
                    if (!$result) {
                        Genre::create([
                            'tmdb_id' => $tgenre['id'],
                            'title' => $tgenre['name'],
                        ]);
                        return Redirect::back()->banner('Genre added successfully');
                    } else {
                        return Redirect::back()->dangerBanner('Genre exists');
                    }
                }
            }
        } else {
            return Redirect::back()->dangerBanner('Api not exists. Please try again');
        }
    }

    public function edit(Genre $genre)
    {
        return Inertia::render('Admin/Genre/Edit', [
            'genre' => $genre
        ]);
    }

    public function update(Genre $genre)
    {
        $genre->update(Request::validate([
            'title' => ['required', 'min:3', 'max:255'],
        ]));
        return redirect()->route('admin.genres.index')->banner("Genre {$genre->title} has been updated.");
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('admin.genres.index')->banner("Genre {$genre->title} has been deleted.");
    }
}
