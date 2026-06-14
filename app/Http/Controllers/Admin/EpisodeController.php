<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $episodes = Episode::latest()->paginate(10);
        return view('admin.episodes.index', compact('episodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.episodes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_link' => 'nullable|url|max:255',
            'short_description' => 'required|string|max:500',
            'long_description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        // Generate slug
        $validated['slug'] = Episode::generateSlug($validated['title']);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('episodes/thumbnails', 'public');
        }

        // Set published_at if publishing
        if ($validated['is_published'] && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        Episode::create($validated);

        return redirect()->route('admin.episodes.index')
            ->with('success', 'Episode created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Episode $episode)
    {
        return view('admin.episodes.show', compact('episode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Episode $episode)
    {
        return view('admin.episodes.edit', compact('episode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Episode $episode)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('episodes')->ignore($episode->id)],
            'youtube_link' => 'nullable|url|max:255',
            'short_description' => 'required|string|max:500',
            'long_description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($episode->thumbnail) {
                Storage::disk('public')->delete($episode->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('episodes/thumbnails', 'public');
        }

        // Set published_at if publishing for the first time
        if ($validated['is_published'] && !$episode->published_at && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        $episode->update($validated);

        return redirect()->route('admin.episodes.index')
            ->with('success', 'Episode updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Episode $episode)
    {
        // Delete thumbnail
        if ($episode->thumbnail) {
            Storage::disk('public')->delete($episode->thumbnail);
        }

        $episode->delete();

        return redirect()->route('admin.episodes.index')
            ->with('success', 'Episode deleted successfully!');
    }
}
