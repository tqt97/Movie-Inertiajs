<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Inertia\Inertia;

class FrontendTagController extends Controller
{
    public function show(Tag $tag)
    {
        return Inertia::render('Frontend/Tag/Index', [
            'tag' => $tag,
            'movies' => $tag->movies()->paginate(12)
        ]);
    }
}
