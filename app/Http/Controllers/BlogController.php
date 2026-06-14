<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sort options
        $sortBy = $request->get('sort', 'published_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'views') {
            $query->orderBy('views_count', $sortOrder);
        } elseif ($sortBy === 'title') {
            $query->orderBy('title', $sortOrder);
        } else {
            $query->orderBy('published_at', $sortOrder);
        }

        $blogPosts = $query->paginate(12)->withQueryString();
        $categories = BlogPost::getCategories();

        return view('blog.index', compact('blogPosts', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(BlogPost $blogPost)
    {
        // Show all posts regardless of published status

        // Increment view count
        $blogPost->incrementViews();

        // Get related posts
        $relatedPosts = BlogPost::where('category', $blogPost->category)
            ->where('id', '!=', $blogPost->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('blog.show', compact('blogPost', 'relatedPosts'));
    }
}
