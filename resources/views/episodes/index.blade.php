@extends('layouts.app')

@php
    $pageTitle = match ($language) {
        'kn' => 'Kannada Podcast Episodes — Business Giseness | Kannada Business Podcast',
        'en' => 'English Podcast Episodes — Business Giseness',
        default => 'All Episodes — Business Giseness | Kannada Business Podcast',
    };
    $pageDescription = match ($language) {
        'kn' => 'Watch Business Giseness episodes in Kannada — honest conversations with Karnataka\'s founders and entrepreneurs about building a business. ಕನ್ನಡದ ಉದ್ಯಮಿಗಳ ನಿಜ ಕಥೆಗಳು.',
        'en' => 'Business Giseness episodes in English — founder stories from Bengaluru and Karnataka about startups, growth, struggle and the lessons behind building something meaningful.',
        default => 'Every episode of Business Giseness, the Kannada business podcast from Bengaluru. Real founder stories from Karnataka in Kannada & English — startups, family businesses, growth and hard-won lessons.',
    };
    $canonical = $language ? route('episodes.index', ['language' => $language]) : route('episodes.index');
@endphp
@section('title', $pageTitle)
@section('meta-description', $pageDescription)
@section('meta_keywords', 'business giseness episodes, Kannada business podcast episodes, Kannada podcast, founder interviews Karnataka, Bengaluru entrepreneurs, startup stories in Kannada')
@section('og_type', 'website')
@section('og_title', $pageTitle)
@section('og_description', $pageDescription)
@section('canonical_url', $episodes->currentPage() > 1 ? $canonical . (str_contains($canonical, '?') ? '&' : '?') . 'page=' . $episodes->currentPage() : $canonical)

@push('head')
<script type="application/ld+json">{!! \App\Support\Seo::json([
    '@context'   => 'https://schema.org',
    '@type'      => 'CollectionPage',
    'name'       => $pageTitle,
    'description'=> $pageDescription,
    'url'        => $canonical,
    'isPartOf'   => ['@id' => \App\Support\Seo::id('website')],
    'about'      => ['@id' => \App\Support\Seo::id('podcast')],
    'inLanguage' => $language === 'en' ? 'en' : ($language === 'kn' ? 'kn' : ['kn', 'en']),
    'mainEntity' => [
        '@type' => 'ItemList',
        'numberOfItems' => $episodes->total(),
        'itemListElement' => $episodes->values()->map(fn ($episode, $i) => [
            '@type'    => 'ListItem',
            'position' => $episodes->firstItem() + $i,
            'url'      => route('episodes.show', $episode),
            'name'     => $episode->title,
        ])->all(),
    ],
]) !!}</script>
<script type="application/ld+json">{!! \App\Support\Seo::json([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Episodes', 'item' => route('episodes.index')],
        $language ? ['@type' => 'ListItem', 'position' => 3, 'name' => \App\Models\Episode::LANGUAGES[$language] . ' episodes', 'item' => $canonical] : null,
    ])),
]) !!}</script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-brand-dark to-gray-800 text-white py-16 bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('images/bg-breadcrumbs.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/80 to-gray-800/80"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <p class="text-brand-gold text-xs font-semibold uppercase tracking-[0.2em] mb-3">Kannada Business Podcast</p>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    @if($language === 'kn')
                        Kannada Podcast Episodes
                    @elseif($language === 'en')
                        English Podcast Episodes
                    @else
                        Business Giseness Episodes
                    @endif
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Honest, long-form conversations with Karnataka's founders and entrepreneurs — in Kannada and English.
                    The struggles, decisions and lessons behind building a business.
                </p>
                <p lang="kn" class="mt-3 text-lg text-gray-400">{{ \App\Support\Seo::TAGLINE_KN }}</p>
                <div class="mt-8 flex justify-center items-center space-x-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-brand-gold">{{ $episodes->total() }}</div>
                        <div class="text-sm text-gray-300">{{ $language ? \App\Models\Episode::LANGUAGES[$language] . ' episodes' : 'Episodes' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Episodes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Language filter -->
        <nav class="mb-8 flex flex-wrap items-center gap-2" aria-label="Filter episodes by language">
            @foreach(['' => 'All episodes', 'kn' => 'Kannada', 'en' => 'English'] as $code => $label)
                <a href="{{ $code ? route('episodes.index', ['language' => $code]) : route('episodes.index') }}"
                   @if(($language ?? '') === $code) aria-current="page" @endif
                   class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ ($language ?? '') === $code ? 'bg-brand-dark text-white border-brand-dark' : 'bg-white text-gray-700 border-gray-200 hover:border-brand-gold hover:text-brand-dark' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        @if($episodes->count() > 0)
            <!-- Filter/Sort Section -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Latest Episodes</h2>
                    <p class="text-gray-600">{{ $episodes->total() }} episodes available</p>
                </div>

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
                            @if($episode->image_url)
                                <img src="{{ $episode->image_url }}"
                                     alt="{{ $episode->title }}"
                                     loading="lazy"
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
                            <div class="absolute bottom-3 left-3 flex gap-2">
                                <span class="bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                    {{ $episode->published_at->format('M d, Y') }}
                                </span>
                                @if($episode->language_label)
                                    <span class="bg-brand-gold text-brand-dark text-xs font-semibold px-2 py-1 rounded">{{ $episode->language_label }}</span>
                                @endif
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
                @php $socials = \App\Support\Seo::socials(); @endphp
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    @isset($socials['youtube'])
                        <a href="{{ $socials['youtube'] }}" target="_blank" rel="noopener noreferrer" class="bg-brand-gold hover:bg-yellow-600 text-brand-dark font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                            <i class="fab fa-youtube mr-2"></i>Subscribe on YouTube
                        </a>
                    @endisset
                    @isset($socials['spotify'])
                        <a href="{{ $socials['spotify'] }}" target="_blank" rel="noopener noreferrer" class="border border-brand-gold text-brand-gold hover:bg-brand-gold hover:text-brand-dark font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                            <i class="fab fa-spotify mr-2"></i>Listen on Spotify
                        </a>
                    @endisset
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
