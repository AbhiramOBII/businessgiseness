@extends('layouts.app')

@php
    $episodeTitle = $episode->meta_title ?: $episode->title;
    $episodeDescription = $episode->meta_description ?: $episode->short_description;
    $episodeGuests = $episode->guests;
@endphp

@section('title', str_contains($episodeTitle, 'Business Giseness') ? $episodeTitle : $episodeTitle . ' | Business Giseness' . ($episode->language === 'kn' ? ' Kannada Podcast' : ' Podcast'))
@section('meta-description', $episodeDescription)
@section('meta_keywords', 'Business Giseness, Kannada business podcast, ' . ($episode->language_label ? $episode->language_label . ' podcast, ' : '') . $episodeGuests->pluck('name')->join(', ') . ($episodeGuests->isNotEmpty() ? ', ' : '') . 'founder interview, Karnataka entrepreneurs')

<!-- Override Open Graph meta tags for this episode -->
@section('og_type', 'video.episode')
@section('og_title', $episodeTitle)
@section('og_description', $episodeDescription)
@section('og_url', route('episodes.show', $episode))
@if($episode->image_url)
@section('og_image', $episode->image_url)
@endif

<!-- Override Twitter Card meta tags for this episode -->
@section('twitter_title', $episodeTitle)
@section('twitter_description', $episodeDescription)
@section('twitter_url', route('episodes.show', $episode))
@if($episode->image_url)
@section('twitter_image', $episode->image_url)
@endif

@section('canonical_url', route('episodes.show', $episode))

@push('head')
<meta property="article:published_time" content="{{ $episode->published_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $episode->updated_at->toIso8601String() }}">
<meta property="article:author" content="{{ \App\Support\Seo::HOST_NAME }}">
<meta property="article:section" content="Podcast">
<meta property="og:image:alt" content="{{ $episode->title }}">
@if($episode->youtube_id)
<meta property="og:video" content="https://www.youtube.com/embed/{{ $episode->youtube_id }}">
@endif

<link rel="preconnect" href="https://www.youtube.com" crossorigin>
<link rel="dns-prefetch" href="//www.youtube.com">

@php
    $episodeUrl = route('episodes.show', $episode);
    $episodeSchema = array_filter([
        '@context'      => 'https://schema.org',
        '@type'         => 'PodcastEpisode',
        '@id'           => $episodeUrl . '#episode',
        'name'          => $episode->title,
        'description'   => $episodeDescription,
        'url'           => $episodeUrl,
        'image'         => $episode->image_url,
        'datePublished' => $episode->published_at->toIso8601String(),
        'dateModified'  => $episode->updated_at->toIso8601String(),
        'inLanguage'    => $episode->schema_language,
        'partOfSeries'  => ['@id' => \App\Support\Seo::id('podcast')],
        'publisher'     => ['@id' => \App\Support\Seo::id('organization')],
        'author'        => ['@id' => \App\Support\Seo::id('host')],
        'actor'         => $episodeGuests->map(fn ($guest) => ['@type' => 'Person', 'name' => $guest->name, 'url' => route('guests.show', $guest)])->values()->all() ?: null,
        'associatedMedia' => $episode->youtube_id ? array_filter([
            '@type'        => 'VideoObject',
            'name'         => $episode->title,
            'description'  => $episode->short_description,
            'thumbnailUrl' => $episode->image_url,
            'uploadDate'   => $episode->published_at->toIso8601String(),
            'embedUrl'     => 'https://www.youtube.com/embed/' . $episode->youtube_id,
            'url'          => $episode->youtube_link,
            'inLanguage'   => $episode->schema_language,
        ]) : null,
    ]);
@endphp
<script type="application/ld+json">{!! \App\Support\Seo::json($episodeSchema) !!}</script>

<script type="application/ld+json">{!! \App\Support\Seo::json([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Episodes', 'item' => route('episodes.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $episode->title, 'item' => $episodeUrl],
    ],
]) !!}</script>
@endpush

@section('content')
<div class="min-h-screen bg-white">
    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav aria-label="Breadcrumb" class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-brand-gold transition-colors duration-200">Home</a>
                <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                <a href="{{ route('episodes.index') }}" class="hover:text-brand-gold transition-colors duration-200">Episodes</a>
                <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                <span class="text-gray-900 font-medium" aria-current="page">{{ Str::limit($episode->title, 50) }}</span>
            </nav>
        </div>
    </div>

    <!-- Episode Article -->
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Episode Header -->
        <header class="text-center mb-12">
            <!-- Episode Badge -->
            <div class="flex justify-center items-center space-x-4 mb-6">
                <span class="bg-brand-gold text-brand-dark text-sm font-semibold px-4 py-2 rounded-full">
                    <i class="fas fa-podcast mr-2"></i>Episode
                </span>
                @if($episode->language_label)
                    <a href="{{ route('episodes.index', ['language' => $episode->language === 'en' ? 'en' : 'kn']) }}"
                       class="border border-gray-300 text-gray-700 hover:border-brand-gold text-sm font-semibold px-4 py-2 rounded-full transition-colors">
                        <i class="fas fa-language mr-1"></i>{{ $episode->language_label }}
                    </a>
                @endif
                <span class="text-gray-500 text-sm">
                    Published <time datetime="{{ $episode->published_at->toISOString() }}">{{ $episode->published_at->format('F j, Y') }}</time>
                </span>
            </div>
            
            <!-- Episode Title -->
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $episode->title }}
            </h1>
            
            <!-- Short Description -->
            <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto mb-8">
                {{ $episode->short_description }}
            </p>

            @if($episodeGuests->isNotEmpty())
                <p class="text-gray-600 mb-8">
                    <span class="text-gray-500">Featuring</span>
                    @foreach($episodeGuests as $guest)
                        <a href="{{ route('guests.show', $guest) }}" class="font-semibold text-brand-dark hover:text-brand-gold underline decoration-brand-gold/40 underline-offset-4">{{ $guest->name }}</a>@if(!$loop->last), @endif
                    @endforeach
                </p>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-6 sm:space-y-0 sm:space-x-8">
                @if($episode->youtube_link)
                    <a href="{{ $episode->youtube_link }}" 
                       target="_blank" 
                       class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center mr-2">
                        <i class="fab fa-youtube mr-2"></i>Watch on YouTube
                    </a>
                @endif
                
                <button class="bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center mr-2">
                    <i class="fas fa-play mr-2"></i>Listen Now
                </button>
                
            
            </div>
        </header>

        <!-- YouTube Video Embed -->
        @if($episode->youtube_link)
            <section class="mb-12">
                <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden shadow-lg">
                    @php
                        // Extract YouTube video ID from URL
                        $videoId = null;
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $episode->youtube_link, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    
                    @if($videoId)
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $videoId }}" 
                            class="w-full h-full" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            title="{{ $episode->title }} - Video"
                            loading="lazy"
                           >
                        </iframe>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <a href="{{ $episode->youtube_link }}" 
                               target="_blank" 
                               class="bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-8 rounded-lg transition-colors duration-200">
                                <i class="fab fa-youtube mr-2"></i>Watch on YouTube
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Episode Content -->
        <section class="prose prose-lg prose-gray max-w-none mb-12" aria-labelledby="episode-content">
            <h2 id="episode-content" class="sr-only">Episode Description</h2>
            <div class="text-gray-800 leading-relaxed text-lg whitespace-pre-line">
                {!! $episode->long_description !!}
            </div>
        </section>

      

        <!-- Share Section -->
        <section class="border-t border-gray-200 pt-12 mb-12">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Share This Episode</h3>
                <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                    Help others discover this content by sharing it on your favorite platform
                </p>
                <div class="flex justify-center items-center space-x-6">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($episode->title) }}&url={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="bg-brand-dark hover:bg-gray-800 text-white p-4 rounded-lg transition-colors duration-200 flex items-center justify-center w-14 h-14 shadow-md hover:shadow-lg">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="bg-brand-dark hover:bg-gray-800 text-white p-4 rounded-lg transition-colors duration-200 flex items-center justify-center w-14 h-14 shadow-md hover:shadow-lg">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="bg-brand-dark hover:bg-gray-800 text-white p-4 rounded-lg transition-colors duration-200 flex items-center justify-center w-14 h-14 shadow-md hover:shadow-lg">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                    <button onclick="copyToClipboard('{{ request()->url() }}')" 
                            class="bg-brand-gold hover:bg-yellow-600 text-brand-dark p-4 rounded-lg transition-colors duration-200 flex items-center justify-center w-14 h-14 shadow-md hover:shadow-lg">
                        <i class="fas fa-link text-lg"></i>
                    </button>
                </div>
            </div>
        </section>
    </article>

    <!-- Related Episodes -->
    <section class="bg-gray-50 py-16" aria-labelledby="related-episodes">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 id="related-episodes" class="text-3xl font-bold text-gray-900 mb-4">More Episodes</h2>
                <p class="text-gray-600">Discover more insights and stories from our podcast</p>
            </div>
            
            @php
                $relatedEpisodes = \App\Models\Episode::published()
                    ->where('id', '!=', $episode->id)
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            @endphp

            @if($relatedEpisodes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    @foreach($relatedEpisodes as $relatedEpisode)
                        <article class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                            <div class="aspect-video bg-gray-200 relative overflow-hidden">
                                @if($relatedEpisode->thumbnail)
                                    <img src="{{ $relatedEpisode->thumbnail_url }}" 
                                         alt="{{ $relatedEpisode->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-gold to-yellow-600">
                                        <i class="fas fa-podcast text-2xl text-white"></i>
                                    </div>
                                @endif
                                
                                @if($relatedEpisode->youtube_link)
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-red-600 text-white p-1 rounded text-xs">
                                            <i class="fab fa-youtube"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="font-bold text-gray-900 mb-3 line-clamp-2 text-lg">
                                    <a href="{{ route('episodes.show', $relatedEpisode->slug) }}" 
                                       class="hover:text-brand-gold transition-colors duration-200">
                                        {{ $relatedEpisode->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $relatedEpisode->short_description }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $relatedEpisode->published_at->format('M d, Y') }}</span>
                                    <span>{{ $relatedEpisode->published_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <div class="text-center">
                    <a href="{{ route('episodes.index') }}" 
                       class="inline-flex items-center bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-4 px-8 rounded-xl transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>View All Episodes
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-podcast text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 mb-6">This is our first episode! More coming soon.</p>
                        <a href="{{ route('episodes.index') }}" 
                           class="inline-flex items-center bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Episodes
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a temporary success message
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-gray-600');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600');
        }, 2000);
    });
}

function shareEpisode() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $episode->title }}',
            text: '{{ $episode->short_description }}',
            url: window.location.href
        });
    } else {
        // Fallback to copy URL
        copyToClipboard(window.location.href);
    }
}

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Blog-style typography */
.prose {
    line-height: 1.75;
}

.prose p {
    margin-bottom: 1.25em;
}

.prose h2, .prose h3, .prose h4 {
    margin-top: 2em;
    margin-bottom: 1em;
    font-weight: 700;
}

/* Smooth transitions */
* {
    scroll-behavior: smooth;
}

/* Custom focus styles */
button:focus, a:focus {
    outline: 2px solid #ba933e;
    outline-offset: 2px;
}
</style>
@endsection
