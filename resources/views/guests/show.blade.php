@extends('layouts.app')

@section('title', $guest->name . ' - Business Giseness Podcast Guest')
@section('meta-description', $guest->short_description ?: 'Learn about ' . $guest->name . ', a featured guest on the Business Giseness Podcast sharing insights on entrepreneurship and business success.')

@push('head')
<meta property="og:title" content="{{ $guest->name }} - Business Giseness Podcast Guest">
<meta property="og:description" content="{{ $guest->short_description ?: 'Learn about ' . $guest->name . ', a featured guest on the Business Giseness Podcast.' }}">
@if($guest->photo_url)
<meta property="og:image" content="{{ $guest->photo_url }}">
@endif
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="profile">

<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $guest->name,
    'description' => $guest->short_description ?: 'Guest on Business Giseness Podcast',
    'url' => route('guests.show', $guest),
    'image' => $guest->photo_url ?: null,
    'sameAs' => $guest->website || $guest->social_links ? array_filter([
        $guest->website,
        ...collect($guest->social_links)->pluck('url')->toArray()
    ]) : null,
    'worksFor' => [
        '@type' => 'Organization',
        'name' => 'Business Giseness Podcast'
    ],
    'performerIn' => $guest->episodes->count() > 0 ? $guest->episodes->take(5)->map(function($episode) {
        return [
            '@type' => 'PodcastEpisode',
            'name' => $episode->title,
            'url' => route('episodes.show', $episode),
            'datePublished' => $episode->published_at->toISOString()
        ];
    })->toArray() : null
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<!-- Breadcrumbs -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-brand-gold transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('guests.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-brand-gold md:ml-2 transition-colors duration-200">
                            Guests
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate max-w-xs">
                            {{ $guest->name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- 1. Guest Name -->
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $guest->name }}</h1>
            @if($guest->is_featured)
                <span class="inline-flex items-center bg-brand-gold text-white px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-star mr-2"></i>Featured Guest
                </span>
            @endif
        </div>

        <!-- 2. Guest Short Description -->
        @if($guest->short_description)
        <div class="text-center mb-12">
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">{{ $guest->short_description }}</p>
        </div>
        @endif

        <!-- 3. Guest Social Links -->
        @if($guest->social_links)
        <div class="text-center mb-12">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Connect with {{ $guest->name }}</h2>
            <div class="flex justify-center space-x-6">
                @foreach($guest->social_links as $platform => $link)
                    <a href="{{ $link['url'] }}" 
                       target="_blank" 
                       class="bg-white hover:bg-gray-50 border border-gray-200 p-4 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group"
                       title="{{ $link['label'] }}">
                        <i class="{{ $link['icon'] }} text-2xl text-gray-600 group-hover:text-brand-gold transition-colors duration-200"></i>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 4. Guest Image -->
        <div class="text-center mb-12">
            @if($guest->photo_url)
                <img src="{{ $guest->photo_url }}" 
                     alt="{{ $guest->name }}" 
                     class="w-80 h-80 rounded-2xl object-cover mx-auto shadow-2xl">
            @else
                <div class="w-80 h-80 rounded-2xl bg-gradient-to-br from-brand-gold to-yellow-600 flex items-center justify-center mx-auto shadow-2xl">
                    <i class="fas fa-user text-white text-8xl"></i>
                </div>
            @endif
        </div>

        <!-- 5. Guest Description -->
        @if($guest->description)
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 ">About {{ $guest->name }}</h2>
            <div class="prose max-w-none text-gray-700 leading-relaxed ">
                {!! $guest->description !!}
            </div>
        </div>
        @endif

        <!-- 6. Episode Videos -->
        @if($guest->episodes->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Episodes Featuring {{ $guest->name }}
                </h2>
                <span class="inline-flex items-center bg-brand-gold text-white px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $guest->episodes->count() }} {{ Str::plural('Episode', $guest->episodes->count()) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($guest->episodes as $episode)
                <div class="bg-gray-50 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Episode Thumbnail -->
                    <div class="relative aspect-video">
                        @if($episode->thumbnail_url)
                            <img src="{{ $episode->thumbnail_url }}" 
                                 alt="{{ $episode->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-brand-gold to-yellow-600 flex items-center justify-center">
                                <i class="fas fa-podcast text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 hover:opacity-100 transition-opacity duration-200">
                            <div class="bg-white bg-opacity-90 rounded-full p-4">
                                <i class="fas fa-play text-brand-gold text-2xl ml-1"></i>
                            </div>
                        </div>

                        <!-- Role Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $episode->pivot->is_host ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $episode->pivot->is_host ? 'Host' : 'Guest' }}
                            </span>
                        </div>
                    </div>

                    <!-- Episode Info -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                            <a href="{{ route('episodes.show', $episode) }}" 
                               class="hover:text-brand-gold transition-colors duration-200">
                                {{ $episode->title }}
                            </a>
                        </h3>
                        
                        @if($episode->short_description)
                            <p class="text-gray-600 mb-4 text-sm line-clamp-3">{{ $episode->short_description }}</p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $episode->published_at->format('M j, Y') }}
                            </span>
                            @if($episode->duration)
                                <span>
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $episode->duration }}
                                </span>
                            @endif
                        </div>

                        <!-- YouTube Link -->
                        @if($episode->youtube_link)
                        <div class="mt-4">
                            <a href="{{ $episode->youtube_link }}" 
                               target="_blank"
                               class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fab fa-youtube mr-2"></i>
                                Watch on YouTube
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back to Guests -->
        <div class="text-center mt-12">
            <a href="{{ route('guests.index') }}" 
               class="inline-flex items-center bg-brand-gold hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to All Guests
            </a>
        </div>
    </div>
</div>
@endsection
