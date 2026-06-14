<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of guests.
     */
    public function index(Request $request)
    {
        $query = Guest::with(['episodes' => function ($query) {
            $query->where('is_published', true)->orderBy('published_at', 'desc');
        }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by featured
        if ($request->get('featured') === '1') {
            $query->featured();
        }

        // Order guests
        $sortBy = $request->get('sort', 'featured_first');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'episodes':
                $query->withCount(['episodes' => function ($q) {
                    $q->where('is_published', true);
                }])->orderBy('episodes_count', 'desc');
                break;
            default: // featured_first
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('sort_order')
                      ->orderBy('name');
        }

        $guests = $query->paginate(12)->withQueryString();

        // Get featured guests for hero section
        $featuredGuests = Guest::featured()
            ->with(['episodes' => function ($query) {
                $query->where('is_published', true)->latest('published_at')->limit(3);
            }])
            ->limit(6)
            ->get();

        return view('guests.index', compact('guests', 'featuredGuests'));
    }

    /**
     * Display the specified guest.
     */
    public function show(Guest $guest)
    {
        // Load guest with published episodes
        $guest->load([
            'episodes' => function ($query) {
                $query->where('is_published', true)
                      ->orderBy('published_at', 'desc');
            }
        ]);

        // Get related guests (guests who appeared in same episodes)
        $relatedGuests = Guest::whereHas('episodes', function ($query) use ($guest) {
            $query->whereIn('episodes.id', $guest->episodes->pluck('id'))
                  ->where('is_published', true);
        })
        ->where('id', '!=', $guest->id)
        ->withCount(['episodes' => function ($q) {
            $q->where('is_published', true);
        }])
        ->limit(6)
        ->get();

        // Get latest episodes for sidebar
        $latestEpisodes = \App\Models\Episode::published()
            ->with(['guests'])
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('guests.show', compact('guest', 'relatedGuests', 'latestEpisodes'));
    }
}
