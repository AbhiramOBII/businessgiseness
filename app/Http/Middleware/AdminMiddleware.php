<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // Check if user is admin
        if (!auth()->user()->is_admin) {
            // Redirect non-admin users to regular dashboard
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
        }
        
        return $next($request);
    }
}
