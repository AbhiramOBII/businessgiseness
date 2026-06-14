<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guest::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
        }

        // Filter by featured status
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->get('featured') === '1');
        }

        // Sort options
        $sortBy = $request->get('sort', 'sort_order');
        $sortDirection = $request->get('direction', 'asc');
        
        if ($sortBy === 'episode_count') {
            $query->withCount('episodes')->orderBy('episodes_count', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $guests = $query->paginate(15)->appends($request->query());

        return view('admin.guests.index', compact('guests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $episodes = Episode::published()->orderBy('title')->get();
        return view('admin.guests.create', compact('episodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:guests,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'episodes' => 'nullable|array',
            'episodes.*' => 'exists:episodes,id',
            'episode_roles' => 'nullable|array',
            'episode_orders' => 'nullable|array',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('guests', 'public');
        }

        // Set default sort order
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Guest::max('sort_order') + 1;
        }

        $guest = Guest::create($validated);

        // Attach episodes if provided
        if (!empty($validated['episodes'])) {
            $episodeData = [];
            foreach ($validated['episodes'] as $index => $episodeId) {
                $episodeData[$episodeId] = [
                    'is_host' => isset($request->episode_roles[$index]) && $request->episode_roles[$index] === 'host',
                    'sort_order' => $request->episode_orders[$index] ?? 0,
                ];
            }
            $guest->episodes()->attach($episodeData);
        }

        return redirect()->route('admin.guests.index')
                        ->with('success', 'Guest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        $guest->load(['episodes' => function($query) {
            $query->orderBy('pivot_sort_order');
        }]);

        return view('admin.guests.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        $episodes = Episode::published()->orderBy('title')->get();
        $guest->load('episodes');
        
        return view('admin.guests.edit', compact('guest', 'episodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('guests')->ignore($guest->id)],
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'episodes' => 'nullable|array',
            'episodes.*' => 'exists:episodes,id',
            'episode_roles' => 'nullable|array',
            'episode_orders' => 'nullable|array',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $guest->id);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($guest->photo) {
                Storage::disk('public')->delete($guest->photo);
            }
            $validated['photo'] = $request->file('photo')->store('guests', 'public');
        }

        $guest->update($validated);

        // Sync episodes
        if (isset($validated['episodes'])) {
            $episodeData = [];
            foreach ($validated['episodes'] as $index => $episodeId) {
                $episodeData[$episodeId] = [
                    'is_host' => isset($request->episode_roles[$index]) && $request->episode_roles[$index] === 'host',
                    'sort_order' => $request->episode_orders[$index] ?? 0,
                ];
            }
            $guest->episodes()->sync($episodeData);
        } else {
            $guest->episodes()->detach();
        }

        return redirect()->route('admin.guests.index')
                        ->with('success', 'Guest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        // Delete photo if exists
        if ($guest->photo) {
            Storage::disk('public')->delete($guest->photo);
        }

        // Detach from episodes
        $guest->episodes()->detach();

        $guest->delete();

        return redirect()->route('admin.guests.index')
                        ->with('success', 'Guest deleted successfully.');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Guest $guest)
    {
        $guest->update(['is_featured' => !$guest->is_featured]);

        $status = $guest->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Guest {$status} successfully.");
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Guest::where('slug', $slug)->when($ignoreId, function($query, $ignoreId) {
            return $query->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
