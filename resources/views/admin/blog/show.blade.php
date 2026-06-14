@extends('admin.layout')

@section('title', 'View Blog Post')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $blogPost->title }}</h1>
            <div class="flex items-center space-x-4 mt-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $blogPost->category }}
                </span>
                @if($blogPost->is_published)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Published
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-edit mr-1"></i>Draft
                    </span>
                @endif
            </div>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.blog.edit', $blogPost) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Post
            </a>
            <a href="{{ route('admin.blog.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Posts
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Featured Image -->
            @if($blogPost->thumbnail_url)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <img src="{{ $blogPost->thumbnail_url }}" 
                         alt="{{ $blogPost->title }}" 
                         class="w-full h-64 object-cover">
                </div>
            @endif

            <!-- Post Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Short Description</h2>
                <p class="text-gray-700 mb-6">{{ $blogPost->short_description }}</p>
                
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Content</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($blogPost->description)) !!}
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">SEO Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Meta Title</h3>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">
                            {{ $blogPost->meta_title ?: 'Using post title: ' . $blogPost->title }}
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Meta Description</h3>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">
                            {{ $blogPost->meta_description ?: 'Using short description' }}
                        </p>
                    </div>
                    
                    @if($blogPost->meta_keywords)
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Meta Keywords</h3>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $blogPost->meta_keywords }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Social Media Preview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Social Media Preview</h2>
                
                <!-- Facebook Preview -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Facebook / Open Graph</h3>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex">
                            @if($blogPost->thumbnail_url)
                                <img src="{{ $blogPost->thumbnail_url }}" 
                                     alt="{{ $blogPost->title }}" 
                                     class="w-20 h-20 object-cover rounded mr-4">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-600 text-sm">{{ $blogPost->og_title }}</h4>
                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($blogPost->og_description, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ request()->getHost() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Twitter Preview -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Twitter Card</h3>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        @if($blogPost->thumbnail_url)
                            <img src="{{ $blogPost->thumbnail_url }}" 
                                 alt="{{ $blogPost->title }}" 
                                 class="w-full h-32 object-cover rounded mb-3">
                        @endif
                        <h4 class="font-semibold text-gray-900 text-sm">{{ $blogPost->twitter_title }}</h4>
                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($blogPost->twitter_description, 120) }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ request()->getHost() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
                
                <div class="space-y-3">
                    <!-- Toggle Published -->
                    <form method="POST" action="{{ route('admin.blog.toggle-published', $blogPost) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $blogPost->is_published ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-{{ $blogPost->is_published ? 'eye-slash' : 'check' }} mr-2"></i>
                            {{ $blogPost->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>

                    <!-- Duplicate -->
                    <form method="POST" action="{{ route('admin.blog.duplicate', $blogPost) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-copy mr-2"></i>
                            Duplicate Post
                        </button>
                    </form>

                    <!-- Delete -->
                    <form method="POST" action="{{ route('admin.blog.destroy', $blogPost) }}" class="w-full" 
                          onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Post
                        </button>
                    </form>
                </div>
            </div>

            <!-- Post Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Statistics</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Views:</span>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($blogPost->views_count) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Reading Time:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $blogPost->reading_time_text ?? 'Not calculated' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sort Order:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $blogPost->sort_order }}</span>
                    </div>
                </div>
            </div>

            <!-- Publishing Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Publishing Info</h2>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-sm text-gray-600">Status:</span>
                        <div class="mt-1">
                            @if($blogPost->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Published
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-edit mr-1"></i>Draft
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Created:</span>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $blogPost->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    
                    @if($blogPost->published_at)
                        <div>
                            <span class="text-sm text-gray-600">Published:</span>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $blogPost->published_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <span class="text-sm text-gray-600">Last Updated:</span>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $blogPost->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- URL Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">URL Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-sm text-gray-600">Slug:</span>
                        <p class="text-sm font-mono text-gray-900 mt-1 bg-gray-50 p-2 rounded">{{ $blogPost->slug }}</p>
                    </div>
                    
                    @if($blogPost->is_published)
                        <div>
                            <span class="text-sm text-gray-600">Public URL:</span>
                            <p class="text-sm text-blue-600 mt-1 break-all">
                                <a href="#" class="hover:underline">
                                    {{ url('/blog/' . $blogPost->slug) }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
