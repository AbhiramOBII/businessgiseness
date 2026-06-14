<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Public Routes - Completely open for guests, no session requirements
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/about-business-giseness-podcast', function () {
    return view('about');
})->name('about-business-giseness-podcast');

// Legal Pages
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-use', function () {
    return view('legal.terms-of-use');
})->name('terms-of-use');

Route::get('/disclaimer', function () {
    return view('legal.disclaimer');
})->name('disclaimer');

// Public Episodes Routes
Route::get('/episodes', [EpisodeController::class, 'index'])->name('episodes.index');
Route::get('/episodes/{episode:slug}', [EpisodeController::class, 'show'])->name('episodes.show');

// Public Guest Routes
Route::get('/guests', [\App\Http\Controllers\GuestController::class, 'index'])->name('guests.index');
Route::get('/guests/{guest:slug}', [\App\Http\Controllers\GuestController::class, 'show'])->name('guests.show');

// Public Blog Routes
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost:slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Newsletter Routes
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Test route to verify guest access works without sessions
Route::get('/test-guest', function () {
    return response()->json([
        'message' => 'Guest access working perfectly!',
        'is_guest' => !auth()->check(),
        'timestamp' => now()->toDateTimeString()
    ]);
})->name('test-guest');

// User Dashboard (from Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Profile Routes (from Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Login (guest only)
Route::get('/admin/login', function () {
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return view('admin.login');
})->name('admin.login');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/content', [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content');
    Route::post('/content/hero', [\App\Http\Controllers\Admin\ContentController::class, 'updateHero'])->name('content.hero');
    Route::post('/content/site', [\App\Http\Controllers\Admin\ContentController::class, 'updateSite'])->name('content.site');
    Route::post('/content/social', [\App\Http\Controllers\Admin\ContentController::class, 'updateSocial'])->name('content.social');
    
    // Episode Management
    Route::resource('episodes', \App\Http\Controllers\Admin\EpisodeController::class);
    
    // Guest Management
    Route::resource('guests', \App\Http\Controllers\Admin\GuestController::class);
    Route::post('guests/{guest}/toggle-featured', [\App\Http\Controllers\Admin\GuestController::class, 'toggleFeatured'])->name('guests.toggle-featured');
    
    // Blog Management
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class)->parameters(['blog' => 'blogPost']);
    Route::post('blog/{blogPost}/toggle-published', [\App\Http\Controllers\Admin\BlogController::class, 'togglePublished'])->name('blog.toggle-published');
    Route::post('blog/{blogPost}/duplicate', [\App\Http\Controllers\Admin\BlogController::class, 'duplicate'])->name('blog.duplicate');
    
    // Newsletter Subscription Management
    Route::get('newsletter-subscriptions', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'index'])->name('newsletter-subscriptions.index');
    Route::get('newsletter-subscriptions/{newsletterSubscription}', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'show'])->name('newsletter-subscriptions.show');
    Route::get('newsletter-subscriptions/{newsletterSubscription}/edit', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'edit'])->name('newsletter-subscriptions.edit');
    Route::put('newsletter-subscriptions/{newsletterSubscription}', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'update'])->name('newsletter-subscriptions.update');
    Route::delete('newsletter-subscriptions/{newsletterSubscription}', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'destroy'])->name('newsletter-subscriptions.destroy');
    Route::post('newsletter-subscriptions/{newsletterSubscription}/toggle-status', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'toggleStatus'])->name('newsletter-subscriptions.toggle-status');
    Route::get('newsletter-subscriptions-export', [\App\Http\Controllers\Admin\NewsletterSubscriptionController::class, 'export'])->name('newsletter-subscriptions.export');
});

require __DIR__.'/auth.php';
