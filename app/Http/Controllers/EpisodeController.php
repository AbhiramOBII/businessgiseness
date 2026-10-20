<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    /**
     * Display a listing of published episodes.
     */
    public function index(Request $request)
    {
        $language = in_array($request->get('language'), ['kn', 'en'], true) ? $request->get('language') : null;

        $episodes = Episode::published()
            ->when($language, fn ($query) => $query->inLanguage($language))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('episodes.index', compact('episodes', 'language'));
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
