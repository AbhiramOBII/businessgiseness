<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with latest episodes and blog posts.
     */
    public function index()
    {
        // Get the latest 5 episodes
        $latestEpisodes = Episode::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Get the latest 6 blog posts
        $latestBlogs = BlogPost::published()
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Get total episode count for stats
        $totalEpisodes = Episode::published()->count();

        return view('home', compact('latestEpisodes', 'latestBlogs', 'totalEpisodes'));
    }

    /**
     * Landing page for "Kannada business podcast" searches.
     */
    public function kannadaPodcast()
    {
        $kannadaEpisodes = Episode::published()
            ->inLanguage('kn')
            ->latest('published_at')
            ->limit(9)
            ->get();

        $recentEpisodes = Episode::published()
            ->whereNotIn('id', $kannadaEpisodes->pluck('id'))
            ->latest('published_at')
            ->limit(6)
            ->get();

        $totalEpisodes = Episode::published()->count();

        return view('kannada-podcast', compact('kannadaEpisodes', 'recentEpisodes', 'totalEpisodes'));
    }
}
