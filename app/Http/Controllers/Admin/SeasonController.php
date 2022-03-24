<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SeasonController extends Controller
{
    public function index(TvShow $tvShow)
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/TvShow/Season/Index', [
            'seasons' => Season::query()
                ->where('tv_show_id', $tvShow->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->when(Request::has('column'), function ($query) {
                    $query->orderBy(Request::input('column'), Request::input('direction'));
                })
                ->paginate($perPage)
                ->withQueryString(),
            'filters' => Request::only(['search', 'perPage', 'column', 'direction']),
            'tvShow' => $tvShow
        ]);
    }
    public function store(TvShow $tvShow)
    {
        if (!Request::input('season_number')) {
            return Redirect::back()->dangerBanner('Season Number is empty. Please enter a valid Season number');
        }
        if (!is_numeric(Request::input('season_number'))) {
            return Redirect::back()->dangerBanner('Season Number is a number. Please try again');
        }
        $season = $tvShow->seasons()->where('season_number', Request::input('season_number'))->exists();
        if ($season) {
            return Redirect::back()->dangerBanner('Season exists');
        }
        $newSeason = Http::asJson()
            ->get(config('services.tmdb.endpoint') . 'tv/' . $tvShow->tmdb_id . '/season/' . Request::input('season_number') . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');
        if ($newSeason->successful()) {
            Season::create([
                'tv_show_id' => $tvShow->id,
                'tmdb_id' => $newSeason['id'],
                'name'    => $newSeason['name'],
                'poster_path' => $newSeason['poster_path'],
                'season_number' => $newSeason['season_number']
            ]);
            return Redirect::back()->banner('Season created successfully');
        } else {
            return Redirect::back()->dangerBanner('Api not exists. Please try again');
        }
    }
    public function edit(TvShow $tvShow, Season $season)
    {
        return Inertia::render('Admin/TvShow/Season/Edit', [
            'season' => $season,
            'tvShow' => $tvShow
        ]);
    }

    public function update(TvShow $tvShow, Season $season)
    {
        $season->update(Request::validate([
            'name' => 'required',
            'poster_path' => 'required'
        ]));
        return Redirect::route('admin.seasons.index', $tvShow->id)->banner('Season updated successfully');
    }
    public function destroy(TvShow $tvShow, Season $season)
    {
        $season->delete();
        return Redirect::route('admin.seasons.index', $tvShow->id)->banner('Season deleted successfully');
    }
}
