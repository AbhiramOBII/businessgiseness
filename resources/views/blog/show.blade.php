@extends('layouts.app')

@section('title', $blogPost->meta_title)
@section('meta-description', $blogPost->meta_description)
@section('meta_keywords', $blogPost->meta_keywords)

@push('head')
<!-- Open Graph Tags -->
<meta property="og:title" content="{{ $blogPost->og_title }}">
<meta property="og:description" content="{{ $blogPost->og_description }}">
<meta property="og:image" content="{{ $blogPost->og_image }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta property="article:published_time" content="{{ $blogPost->published_at->toISOString() }}">
<meta property="article:author" content="Business Giseness">
<meta property="article:section" content="{{ $blogPost->category }}">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $blogPost->twitter_title }}">
<meta name="twitter:description" content="{{ $blogPost->twitter_description }}">
<meta name="twitter:image" content="{{ $blogPost->twitter_image }}">

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $blogPost->title,
    'description' => $blogPost->short_description,
    'image' => $blogPost->thumbnail_url,
    'author' => [
        '@type' => 'Person',
        'name'  => 'Abhiram Chandramohan',
        'url'   => route('about-business-giseness-podcast'),
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Business Giseness',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/business-giseness-logo.webp')
        ]
    ],
    'datePublished' => $blogPost->published_at->toISOString(),
    'dateModified' => $blogPost->updated_at->toISOString(),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url()->current()
    ],
    'articleSection' => $blogPost->category,
    'wordCount' => str_word_count(strip_tags($blogPost->description))
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',  'item' => route('blog.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $blogPost->title, 'item' => url()->current()],
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 py-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-brand-gold transition-colors duration-200">Home</a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <a href="{{ route('blog.index') }}" class="hover:text-brand-gold transition-colors duration-200">Blog</a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-900">{{ Str::limit($blogPost->title, 50) }}</span>
        </nav>
    </div>
</div>

<!-- Article Header -->
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Category & Meta -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-brand-gold text-brand-dark">
                {{ $blogPost->category }}
            </span>
            <time datetime="{{ $blogPost->published_at->toISOString() }}" class="text-gray-600">
                {{ $blogPost->formatted_published_at }}
            </time>
        </div>
        <div class="flex items-center space-x-4 text-sm text-gray-600">
            @if($blogPost->reading_time_text)
                <span class="flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $blogPost->reading_time_text }}
                </span>
            @endif
            <span class="flex items-center">
                <i class="fas fa-eye mr-1"></i>
                {{ number_format($blogPost->views_count) }} views
            </span>
        </div>
    </div>

    <!-- Title -->
    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
        {{ $blogPost->title }}
    </h1>

    <!-- Short Description -->
    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
        {{ $blogPost->short_description }}
    </p>

    <!-- Featured Image -->
    @if($blogPost->thumbnail_url)
        <div class="mb-8">
            <img src="{{ $blogPost->thumbnail_url }}" 
                 alt="{{ $blogPost->title }}" 
                 class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
        </div>
    @endif

    <!-- Social Share Buttons -->
    <div class="flex items-center justify-between mb-8 py-4 border-y border-gray-200">
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-700">Share this article:</span>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blogPost->title) }}" 
               target="_blank"
               class="flex items-center px-3 py-2 bg-brand-dark hover:bg-gray-800 text-white rounded-lg transition-colors duration-200">
                <i class="fab fa-twitter mr-2"></i>
                Twitter
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
               target="_blank"
               class="flex items-center px-3 py-2 bg-brand-dark hover:bg-gray-800 text-white rounded-lg transition-colors duration-200">
                <i class="fab fa-facebook-f mr-2"></i>
                Facebook
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
               target="_blank"
               class="flex items-center px-3 py-2 bg-brand-dark hover:bg-gray-800 text-white rounded-lg transition-colors duration-200">
                <i class="fab fa-linkedin-in mr-2"></i>
                LinkedIn
            </a>
        </div>
        <button onclick="copyToClipboard('{{ url()->current() }}')" 
                class="flex items-center px-3 py-2 bg-brand-gold hover:bg-yellow-600 text-brand-dark rounded-lg transition-colors duration-200">
            <i class="fas fa-link mr-2"></i>
            Copy Link
        </button>
    </div>

    <!-- Article Content -->
    <div class="prose prose-lg max-w-none">
        <div class="blog-content">
            {!! $blogPost->description !!}
        </div>
    </div>

    <!-- Article Footer -->
    <div class="mt-12 pt-8 border-t border-gray-200">
    
            <div class="text-sm text-gray-600">
                Last updated: {{ $blogPost->updated_at->format('M d, Y') }}
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($relatedPosts->count() > 0)
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $relatedPost)
                    <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Featured Image -->
                        <div class="aspect-video bg-gray-200 overflow-hidden">
                            @if($relatedPost->thumbnail_url)
                                <img src="{{ $relatedPost->thumbnail_url }}" 
                                     alt="{{ $relatedPost->title }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-brand-dark to-gray-600 flex items-center justify-center">
                                    <i class="fas fa-blog text-white text-2xl opacity-50"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category & Date -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-brand-gold text-brand-dark">
                                    {{ $relatedPost->category }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $relatedPost->formatted_published_at }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $relatedPost) }}" class="hover:text-brand-gold transition-colors duration-200">
                                    {{ $relatedPost->title }}
                                </a>
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $relatedPost->short_description }}
                            </p>

                            <!-- Read More -->
                            <a href="{{ route('blog.show', $relatedPost) }}" 
                               class="text-brand-gold hover:text-yellow-600 font-medium text-sm transition-colors duration-200">
                                Read More <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Newsletter Signup -->
<div class="bg-brand-dark py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Enjoyed this article?</h2>
        <p class="text-xl text-gray-300 mb-8">
            Subscribe to our newsletter for more insights and stories from the world of entrepreneurship.
        </p>
        <form class="max-w-md mx-auto flex gap-4">
            <input type="email" 
                   placeholder="Enter your email" 
                   class="flex-1 px-4 py-3 border border-gray-600 bg-gray-800 text-white rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-transparent placeholder-gray-400">
            <button type="submit" 
                    class="bg-brand-gold hover:bg-yellow-600 text-brand-dark px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                Subscribe
            </button>
        </form>
    </div>
</div>

<!-- Back to Blog -->
<div class="bg-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <a href="{{ route('blog.index') }}" 
           class="inline-flex items-center text-brand-gold hover:text-yellow-600 font-medium transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to All Articles
        </a>
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

.blog-content {
    line-height: 1.8;
    font-size: 1.125rem;
}

.blog-content p {
    margin-bottom: 1.5rem;
}

.blog-content h1, .blog-content h2, .blog-content h3, .blog-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: bold;
    color: #1f2937;
}

.blog-content h1 { font-size: 2rem; }
.blog-content h2 { font-size: 1.75rem; }
.blog-content h3 { font-size: 1.5rem; }
.blog-content h4 { font-size: 1.25rem; }

.blog-content ul, .blog-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.blog-content li {
    margin-bottom: 0.5rem;
}

.blog-content blockquote {
    border-left: 4px solid #ba933e;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #4b5563;
}

.blog-content code {
    background-color: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.blog-content pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.blog-content pre code {
    background-color: transparent;
    padding: 0;
    color: inherit;
}
</style>
@endpush

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    });
}
</script>
@endpush
