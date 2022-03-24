<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class EpisodeController extends Controller
{
    public function index(TvShow $tvShow, Season $season)
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/TvShow/Season/Episode/Index', [
            'episodes' => Episode::query()
                ->where('season_id', $season->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->when(Request::has('column'), function ($query) {
                    $query->orderBy(Request::input('column'), Request::input('direction'));
                })
                ->paginate($perPage)
                ->withQueryString(),
            'filters' => Request::only(['search', 'perPage', 'column', 'direction']),
            'tvShow' => $tvShow,
            'season' => $season,
        ]);
    }
    public function store(TvShow $tvShow, Season $season)
    {
        if (!Request::input('episode_number')) {
            return Redirect::back()->dangerBanner('Episode Number is empty. Please enter a valid Episode number');
        }
        if (!is_numeric(Request::input('episode_number'))) {
            return Redirect::back()->dangerBanner('Episode Number is a number. Please try again');
        }
        $episode = $season->episodes()->where('episode_number', Request::input('episode_number'))->exists();
        if ($episode) {
            return Redirect::back()->dangerBanner('Episode exists');
        }
        $newEpisode = Http::asJson()->get(config('services.tmdb.endpoint') . 'tv/' . $tvShow->tmdb_id . '/season/' . $season->season_number . '/episode/' . Request::input('episode_number') . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');
        if ($newEpisode->successful()) {
            Episode::create([
                'season_id' => $season->id,
                'tmdb_id' => $newEpisode['id'],
                'name'    => $newEpisode['name'],
                'episode_number' => $newEpisode['episode_number'],
                'overview'  => $newEpisode['overview'],
            ]);
            return Redirect::back()->banner('Episode created successfully');
        } else {
            return Redirect::back()->dangerBanner('Api not exists. Please try again');
        }
    }
    public function edit(TvShow $tvShow, Season $season, Episode $episode)
    {
        return Inertia::render('Admin/TvShow/Season/Episode/Edit', [
            'tvShow' => $tvShow,
            'season' => $season,
            'episode' => $episode
        ]);
    }

    public function update(TvShow $tvShow, Season $season, Episode $episode)
    {
        $validated = Request::validate([
            'name'    => 'required',
            'overview' => 'required',
            'is_public' => 'required'
        ]);
        $episode->update($validated);
        return Redirect::route('admin.episodes.index', [$tvShow->id, $season->id])->banner('Episode updated.');
    }

    public function destroy(TvShow $tvShow, Season $season, Episode $episode)
    {
        $episode->delete();
        return Redirect::route('admin.episodes.index', [$tvShow->id, $season->id])->banner('Episode deleted.');
    }
}
