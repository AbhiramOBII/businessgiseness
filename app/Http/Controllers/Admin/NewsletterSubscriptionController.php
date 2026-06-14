<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Display newsletter subscriptions with search and filtering
     */
    public function index(Request $request)
    {
        $query = NewsletterSubscription::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->active();
            } elseif ($request->get('status') === 'inactive') {
                $query->inactive();
            }
        }

        // Source filter
        if ($request->filled('source')) {
            $query->bySource($request->get('source'));
        }

        // Sorting
        $sortBy = $request->get('sort', 'subscribed_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $subscriptions = $query->paginate(20)->withQueryString();
        $stats = NewsletterSubscription::getStats();

        return view('admin.newsletter-subscriptions.index', compact('subscriptions', 'stats'));
    }

    /**
     * Show subscription details
     */
    public function show(NewsletterSubscription $newsletterSubscription)
    {
        return view('admin.newsletter-subscriptions.show', compact('newsletterSubscription'));
    }

    /**
     * Show edit form
     */
    public function edit(NewsletterSubscription $newsletterSubscription)
    {
        return view('admin.newsletter-subscriptions.edit', compact('newsletterSubscription'));
    }

    /**
     * Update subscription
     */
    public function update(Request $request, NewsletterSubscription $newsletterSubscription)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email,' . $newsletterSubscription->id,
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $newsletterSubscription->update($validated);

        return redirect()
            ->route('admin.newsletter-subscriptions.index')
            ->with('success', 'Newsletter subscription updated successfully.');
    }

    /**
     * Delete subscription
     */
    public function destroy(NewsletterSubscription $newsletterSubscription)
    {
        $newsletterSubscription->delete();

        return redirect()
            ->route('admin.newsletter-subscriptions.index')
            ->with('success', 'Newsletter subscription deleted successfully.');
    }

    /**
     * Toggle subscription status
     */
    public function toggleStatus(NewsletterSubscription $newsletterSubscription)
    {
        if ($newsletterSubscription->is_active) {
            $newsletterSubscription->unsubscribe();
            $message = 'Subscription deactivated successfully.';
        } else {
            $newsletterSubscription->resubscribe();
            $message = 'Subscription reactivated successfully.';
        }

        return redirect()
            ->route('admin.newsletter-subscriptions.index')
            ->with('success', $message);
    }

    /**
     * Export subscriptions to CSV
     */
    public function export(Request $request)
    {
        $query = NewsletterSubscription::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->active();
            } elseif ($request->get('status') === 'inactive') {
                $query->inactive();
            }
        }

        $subscriptions = $query->orderBy('subscribed_at', 'desc')->get();

        $filename = 'newsletter-subscriptions-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Email', 'Name', 'Status', 'Subscribed At', 'Source', 'IP Address']);
            
            // CSV data
            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->email,
                    $subscription->name ?? '',
                    $subscription->status_text,
                    $subscription->formatted_subscribed_at,
                    $subscription->subscription_source,
                    $subscription->ip_address ?? '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
