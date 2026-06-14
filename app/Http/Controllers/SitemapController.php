<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Guest;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate the XML sitemap for public pages.
     */
    public function index(Request $request): Response
    {
        $episodes = Episode::published()
            ->orderByDesc('published_at')
            ->get();

        $guests = Guest::orderBy('name')->get();

        $blogPosts = BlogPost::published()
            ->orderByDesc('published_at')
            ->get();

        return response()
            ->view('sitemap', [
                'episodes'  => $episodes,
                'guests'    => $guests,
                'blogPosts' => $blogPosts,
                'baseUrl'   => $request->getSchemeAndHttpHost(),
            ])
            ->header('Content-Type', 'application/xml');
    }
}
