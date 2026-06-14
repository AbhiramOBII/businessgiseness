@extends('layouts.app')

@section('title', 'All Episodes - Business Giseness Podcast')
@section('meta-description', 'Explore all episodes of Business Giseness — the bilingual founder podcast in English & Kannada. Real stories, raw conversations, and hard-won business insights from founders still in the trenches.')
@section('meta_keywords', 'business giseness episodes, founder podcast episodes, Kannada business podcast, entrepreneur podcast India, startup stories episodes')
@section('og_type', 'website')
@section('og_title', 'All Episodes — Business Giseness Podcast')
@section('og_description', 'Browse every episode of Business Giseness. Real founder stories in English and Kannada — no filters, no fluff.')
@section('canonical_url', route('episodes.index'))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'PodcastSeries',
    'name'        => 'Business Giseness',
    'description' => 'A bilingual podcast show in English and Kannada featuring real founder stories, honest conversations about business, struggle, growth and the journey behind building something meaningful.',
    'url'         => route('episodes.index'),
    'inLanguage'  => ['en', 'kn'],
    'author'      => [
        '@type' => 'Person',
        'name'  => 'Abhiram Chandramohan',
        'url'   => route('about-business-giseness-podcast'),
    ],
    'numberOfEpisodes' => \App\Models\Episode::published()->count(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-brand-dark to-gray-800 text-white py-16 bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('images/bg-breadcrumbs.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/80 to-gray-800/80"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Podcast Episodes
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Dive deep into the world of business with our comprehensive collection of podcast episodes. 
                    Learn from industry experts, successful entrepreneurs, and thought leaders.
                </p>
                <div class="mt-8 flex justify-center items-center space-x-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-brand-gold">{{ $episodes->total() }}</div>
                        <div class="text-sm text-gray-300">Total Episodes</div>
                    </div>
                    <div class="w-px h-12 bg-gray-600"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-brand-gold">{{ \App\Models\Episode::published()->count() }}</div>
                        <div class="text-sm text-gray-300">Published</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Episodes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($episodes->count() > 0)
            <!-- Filter/Sort Section -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Latest Episodes</h2>
                    <p class="text-gray-600">{{ $episodes->total() }} episodes available</p>
                </div>
                
                <!-- Future: Add search and filter options here -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Showing {{ $episodes->firstItem() }}-{{ $episodes->lastItem() }} of {{ $episodes->total() }}</span>
                </div>
            </div>

            <!-- Episodes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($episodes as $episode)
                    <article class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <!-- Episode Thumbnail -->
                        <div class="aspect-video bg-gray-200 relative overflow-hidden">
                            @if($episode->thumbnail)
                                <img src="{{ $episode->thumbnail_url }}" 
                                     alt="{{ $episode->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-gold to-yellow-600">
                                    <i class="fas fa-podcast text-4xl text-white"></i>
                                </div>
                            @endif
                            
                            <!-- YouTube Link Indicator -->
                            @if($episode->youtube_link)
                                <div class="absolute top-3 right-3">
                                    <a href="{{ $episode->youtube_link }}" 
                                       target="_blank" 
                                       class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors duration-200"
                                       title="Watch on YouTube">
                                        <i class="fab fa-youtube text-sm"></i>
                                    </a>
                                </div>
                            @endif
                            
                            <!-- Episode Duration/Date Overlay -->
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                    {{ $episode->published_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Episode Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                <a href="{{ route('episodes.show', $episode->slug) }}" 
                                   class="hover:text-brand-gold transition-colors duration-200">
                                    {{ $episode->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $episode->short_description }}
                            </p>
                            
                            <!-- Episode Meta -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>{{ $episode->published_at->diffForHumans() }}</span>
                                @if($episode->youtube_link)
                                    <span class="flex items-center">
                                        <i class="fab fa-youtube text-red-600 mr-1"></i>
                                        Available on YouTube
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('episodes.show', $episode->slug) }}" 
                                   class="flex-1 bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-2 px-4 rounded-lg text-center transition-colors duration-200">
                                    <i class="fas fa-play mr-2"></i>Listen Now
                                </a>
                                
                             
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $episodes->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-podcast text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Episodes Yet</h3>
                    <p class="text-gray-600 mb-8">
                        We're working hard to bring you amazing content. 
                        Check back soon for our latest podcast episodes!
                    </p>
                    <a href="{{ route('home') }}" 
                       class="bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Call to Action Section -->
    @if($episodes->count() > 0)
        <div class="bg-brand-dark text-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Never Miss an Episode</h2>
                <p class="text-xl text-gray-300 mb-8">
                    Subscribe to stay updated with our latest business insights and entrepreneurial stories.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <!-- Future: Add subscription links -->
                    <a href="#" class="bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        <i class="fas fa-rss mr-2"></i>Subscribe to RSS
                    </a>
                    <a href="#" class="border border-brand-gold text-brand-gold hover:bg-brand-gold hover:text-brand-dark font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        <i class="fab fa-spotify mr-2"></i>Listen on Spotify
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Line clamp utilities for text truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
