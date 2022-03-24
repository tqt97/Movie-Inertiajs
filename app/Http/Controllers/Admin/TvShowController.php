<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TvShow;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TvShowController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/TvShow/Index', [
            'tvShows' => TvShow::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
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
        return Inertia::render('Admin/TvShow/Create');
    }

    public function store()
    {
        if (!Request::input('tvShow_id')) {
            return Redirect::back()->dangerBanner('TvShow ID is empty. Please enter a valid Cast ID');
        }
        if (!is_numeric(Request::input('tvShow_id'))) {
            return Redirect::back()->dangerBanner('TvShow ID is a number. Please try again');
        }
        $tvShow = TvShow::where('tmdb_id', Request::input('tvShow_id'))->exists();
        if ($tvShow) {
            return Redirect::back()->dangerBanner('TvShow exists');
        }
        $api_url = Http::get(config('services.tmdb.endpoint') . 'tv/' . Request::input('tvShow_id') . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');
        if ($api_url->successful()) {
            $newTvShow = $api_url->json();
            TvShow::create([
                'tmdb_id' => $newTvShow['id'],
                'name'    => $newTvShow['name'],
                'poster_path' => $newTvShow['poster_path'],
                'created_year' => $newTvShow['first_air_date']
            ]);
            return Redirect::back()->banner('TvShow created successfully');
        } else {
            return Redirect::back()->dangerBanner('Api not exists. Please try again');
        }
    }

    public function edit(TvShow $tvShow)
    {
        return Inertia::render('Admin/TvShow/Edit', [
            'tvShow' => $tvShow
        ]);
    }

    public function update(TvShow $tvShow)
    {
        $tvShow->update(Request::only(['name', 'poster_path']));
        return Redirect()->route('admin.tv-shows.index')->banner('TvShow updated successfully');
    }

    public function destroy(TvShow $tvShow)
    {
        $tvShow->delete();
        return redirect()->route('admin.tv-shows.index')->banner('TvShow deleted successfully');
    }
}
