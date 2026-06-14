<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Dashboard statistics
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_episodes' => \App\Models\Episode::count(),
            'published_episodes' => \App\Models\Episode::where('is_published', true)->count(),
            'total_pages' => 2, // Home and About pages for now
            'recent_logins' => \App\Models\User::latest('updated_at')->take(5)->get(),
            'recent_episodes' => \App\Models\Episode::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display content management page.
     */
    public function content()
    {
        return view('admin.content');
    }
}
