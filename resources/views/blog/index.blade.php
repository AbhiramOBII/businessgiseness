@extends('layouts.app')

@section('title', 'Blog - Business Giseness')
@section('meta_description', 'Read the latest insights, stories, and tips from Business Giseness. Discover entrepreneurship, business strategy, and success stories from our podcast guests.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-brand-dark to-gray-800 text-white py-16 bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('images/bg-breadcrumbs.webp') }}');">
    <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/80 to-gray-800/80"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Business Giseness Blog</h1>
            <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
                Insights, stories, and strategies from the world of entrepreneurship. 
                Learn from successful founders and business leaders.
            </p>
            
            <!-- Blog Stats -->
            <div class="flex justify-center space-x-8 mt-8">
                <div class="text-center">
                    <div class="text-3xl font-bold text-brand-gold">{{ $blogPosts->total() }}</div>
                    <div class="text-sm text-gray-300">Articles</div>
                </div>
        
            </div>
        </div>
    </div>
</div>



<!-- Blog Posts Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($blogPosts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($blogPosts as $post)
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Featured Image -->
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        @if($post->thumbnail_url)
                            <img src="{{ $post->thumbnail_url }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-brand-dark to-gray-600 flex items-center justify-center">
                                <i class="fas fa-blog text-white text-4xl opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Category & Date -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-brand-gold text-brand-dark">
                                {{ $post->category }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $post->formatted_published_at }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <a href="{{ route('blog.show', $post) }}" class="hover:text-brand-gold transition-colors duration-200">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <!-- Excerpt -->
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $post->short_description }}
                        </p>

                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-4">
                                @if($post->reading_time_text)
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $post->reading_time_text }}
                                    </span>
                                @endif
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ number_format($post->views_count) }}
                                </span>
                            </div>
                            <a href="{{ route('blog.show', $post) }}" 
                               class="text-brand-gold hover:text-yellow-600 font-medium transition-colors duration-200">
                                Read More <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $blogPosts->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <i class="fas fa-blog text-gray-300 text-6xl mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    @if(request()->hasAny(['search', 'category']))
                        No articles found
                    @else
                        No blog posts yet
                    @endif
                </h3>
                <p class="text-gray-600 mb-8">
                    @if(request()->hasAny(['search', 'category']))
                        Try adjusting your search criteria or browse all articles.
                    @else
                        We're working on creating amazing content for you. Check back soon!
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('blog.index') }}" 
                       class="bg-brand-gold hover:bg-yellow-600 text-brand-dark px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                        View All Articles
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Newsletter Signup Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Stay Updated</h2>
        <p class="text-xl text-gray-600 mb-8">
            Get the latest articles and insights delivered straight to your inbox.
        </p>
        <form class="max-w-md mx-auto flex gap-4">
            <input type="email" 
                   placeholder="Enter your email" 
                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-transparent">
            <button type="submit" 
                    class="bg-brand-gold hover:bg-yellow-600 text-brand-dark px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                Subscribe
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
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
@endpush
