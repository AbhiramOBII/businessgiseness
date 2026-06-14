@extends('layouts.app')

@section('title', 'Podcast Guests - Business Giseness Podcast')
@section('meta-description', 'Meet the inspiring guests who have shared their expertise on the Business Giseness Podcast. Discover entrepreneurs, business leaders, and industry experts.')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Podcast Guests - Business Giseness Podcast',
    'description' => 'Meet the inspiring guests who have shared their expertise on the Business Giseness Podcast.',
    'url' => url()->current(),
    'mainEntity' => [
        '@type' => 'ItemList',
        'numberOfItems' => $guests->total(),
        'itemListElement' => $guests->take(10)->map(function($guest, $index) {
            $item = [
                '@type' => 'Person',
                'position' => $index + 1,
                'name' => $guest->name,
                'description' => $guest->short_description ?: 'Guest on Business Giseness Podcast',
                'url' => route('guests.show', $guest)
            ];
            if ($guest->photo_url) {
                $item['image'] = $guest->photo_url;
            }
            return $item;
        })->values()
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
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
                    Podcast Guests
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Meet the inspiring entrepreneurs, business leaders, and industry experts who have shared 
                    their knowledge and experiences on the Business Giseness Podcast.
                </p>
                <div class="mt-8 flex justify-center items-center space-x-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-brand-gold">{{ $guests->total() }}</div>
                        <div class="text-sm text-gray-300">Total Guests</div>
                    </div>
                    <div class="w-px h-12 bg-gray-600"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-brand-gold">{{ \App\Models\Episode::published()->count() }}</div>
                        <div class="text-sm text-gray-300">Total Episodes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- All Guests Grid -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($guests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($guests as $guest)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="relative">
                            @if($guest->photo_url)
                                <img src="{{ $guest->photo_url }}" 
                                     alt="{{ $guest->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-4xl"></i>
                                </div>
                            @endif
                            
                            @if($guest->is_featured)
                                <div class="absolute top-2 right-2">
                                    <span class="bg-brand-gold text-white px-2 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $guest->name }}</h3>
                            
                            @if($guest->short_description)
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($guest->short_description, 80) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <span>
                                    <i class="fas fa-podcast mr-1"></i>
                                    {{ $guest->episodes->count() }} {{ Str::plural('episode', $guest->episodes->count()) }}
                                </span>
                                
                                @if($guest->social_links)
                                    <div class="flex space-x-1">
                                        @foreach(array_slice($guest->social_links, 0, 2) as $platform => $link)
                                            <a href="{{ $link['url'] }}" 
                                               target="_blank" 
                                               class="text-gray-400 hover:text-brand-gold transition-colors duration-200"
                                               title="{{ $link['label'] }}">
                                                <i class="{{ $link['icon'] }} text-xs"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <a href="{{ route('guests.show', $guest) }}" 
                               class="block w-full text-center bg-gray-100 hover:bg-brand-gold hover:text-white text-gray-700 font-medium py-2 rounded-lg transition-colors duration-200">
                                View Profile
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $guests->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-users text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">No Guests Found</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request()->hasAny(['search', 'featured']))
                                No guests match your current search criteria. Try adjusting your filters.
                            @else
                                We haven't featured any guests yet. Check back soon for inspiring conversations with business leaders!
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'featured']))
                            <a href="{{ route('guests.index') }}" 
                               class="inline-flex items-center bg-brand-gold hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>View All Guests
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
