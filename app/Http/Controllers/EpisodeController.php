<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    /**
     * Display a listing of published episodes.
     */
    public function index()
    {
        $episodes = Episode::published()
            ->latest('published_at')
            ->paginate(12);

        return view('episodes.index', compact('episodes'));
    }

    /**
     * Display the specified episode.
     */
    public function show(Episode $episode)
    {
        // Only show published episodes to public
        if (!$episode->is_published) {
            abort(404);
        }

        return view('episodes.show', compact('episode'));
    }
}
