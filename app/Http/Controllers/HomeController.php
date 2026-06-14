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
        $latestEpisodes = Episode::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get the latest 6 blog posts
        $latestBlogs = BlogPost::orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get total episode count for stats
        $totalEpisodes = Episode::count();

        return view('home', compact('latestEpisodes', 'latestBlogs', 'totalEpisodes'));
    }
}
