<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 10;
        return Inertia::render('Admin/Tag/Index', [
            'tags' => Tag::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->when(Request::has('column'), function ($query) {
                    $query->orderBy(Request::input('column'), Request::input('direction'));
                })
                ->paginate($perPage)
                ->withQueryString(),
            'filters' => Request::only(['search', 'perPage','column','direction'])
        ]);
    }
    public function create()
    {
        return Inertia::render('Admin/Tag/Create');
    }
    public function store()
    {
        Tag::create([
            'name' => Request::input('name'),
        ]);
        return Redirect::route('admin.tags.index')->with('flash.banner', 'Tag created successfully');
    }
    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tag/Edit', [
            'tag' => $tag
        ]);
    }
    public function update(Tag $tag)
    {
        $tag->update([
            'name' => Request::input('name'),
        ]);
        return Redirect::route('admin.tags.index')->with('flash.banner', 'Tag updated successfully');
    }
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return Redirect::route('admin.tags.index')->with('flash.banner', 'Tag deleted successfully');
    }
}
