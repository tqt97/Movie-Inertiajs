<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;


class CastController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/Cast/Index', [
            'casts' => Cast::query()
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
        return Inertia::render('Admin/Cast/Create');
    }
    public function store()
    {
        if (!Request::input('cast_id')) {
            return Redirect::back()->dangerBanner('Cast ID is empty. Please enter a valid Cast ID');
        }
        if (!is_numeric(Request::input('cast_id'))) {
            return Redirect::back()->dangerBanner('Cast ID is a number. Please try again');
        }
        $cast = Cast::where('tmdb_id', Request::input('cast_id'))->exists();
        if ($cast) {
            return Redirect::back()->dangerBanner('Cast exists');
        }
        $api_url = Http::get(config('services.tmdb.endpoint') . 'person/' . Request::input('cast_id') . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');
        if ($api_url->successful()) {
            $newCast = $api_url->json();
            Cast::create([
                'tmdb_id' => $newCast['id'],
                'name'    => $newCast['name'],
                'slug'    => Str::slug($newCast['name']),
                'poster_path' => $newCast['profile_path']
            ]);
            return Redirect::back()->banner('Cast created successfully');
        } else {
            return Redirect::back()->dangerBanner('Api not exists. Please try again');
        }
    }
    public function edit(Cast $cast)
    {
        return Inertia::render('Admin/Cast/Edit', [
            'cast' => $cast
        ]);
    }
    public function update(Cast $cast)
    {
        $cast->update(Request::only(['name', 'slug']));
        return Redirect::back()->banner('Cast updated successfully');
    }
    //    {
    //        $cast->update(Request::validate([
    //            'name' => 'required',
    //            'poster_path' => 'required'
    //        ]));
    //        return Redirect::route('admin.casts.index')->with('flash.banner', 'Cast updated successfully');
    //    }
    public function destroy(Cast $cast)
    {
        $cast->delete();
        return Redirect::back()->banner('Cast deleted successfully');
    }
}
