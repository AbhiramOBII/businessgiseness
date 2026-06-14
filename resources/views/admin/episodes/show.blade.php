@extends('admin.layout')

@section('title', $episode->title)
@section('page-title', 'Episode Details')
@section('page-description', 'View episode information')

@section('content')
<div class="max-w-4xl">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.episodes.index') }}" 
           class="text-gray-600 hover:text-gray-800 transition-colors duration-200 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Episodes
        </a>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.episodes.edit', $episode) }}" 
               class="bg-brand-gold hover:bg-brand-gold hover:bg-opacity-90 text-brand-dark px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Edit Episode
            </a>
            
            <form action="{{ route('admin.episodes.destroy', $episode) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this episode? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Episode Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start space-x-6">
                @if($episode->thumbnail)
                    <div class="flex-shrink-0">
                        <img src="{{ $episode->thumbnail_url }}" 
                             alt="{{ $episode->title }}" 
                             class="w-48 h-48 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 mb-3">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $episode->title }}</h1>
                        @if($episode->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Draft
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Slug:</span> 
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $episode->slug }}</code>
                        </div>
                        <div>
                            <span class="font-medium">Created:</span> {{ $episode->created_at->format('M d, Y \a\t g:i A') }}
                        </div>
                        @if($episode->published_at)
                            <div>
                                <span class="font-medium">Published:</span> {{ $episode->published_at->format('M d, Y \a\t g:i A') }}
                            </div>
                        @endif
                        <div>
                            <span class="font-medium">Last Updated:</span> {{ $episode->updated_at->format('M d, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="p-6 space-y-8">
            <!-- YouTube Link -->
            @if($episode->youtube_link)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">YouTube Video</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <i class="fab fa-youtube text-red-600 text-xl"></i>
                            <a href="{{ $episode->youtube_link }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 underline">
                                {{ $episode->youtube_link }}
                            </a>
                            <i class="fas fa-external-link-alt text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Short Description -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Short Description</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700">{{ $episode->short_description }}</p>
                </div>
            </div>

            <!-- Full Description -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Full Description</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($episode->long_description)) !!}
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            @if($episode->meta_title || $episode->meta_description)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">SEO Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                        @if($episode->meta_title)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">Meta Title</h4>
                                <p class="text-gray-700">{{ $episode->meta_title }}</p>
                            </div>
                        @endif
                        
                        @if($episode->meta_description)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">Meta Description</h4>
                                <p class="text-gray-700">{{ $episode->meta_description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Technical Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Technical Details</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-900">Episode ID:</span>
                            <span class="text-gray-700">{{ $episode->id }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">URL Slug:</span>
                            <code class="bg-white px-2 py-1 rounded text-xs border">{{ $episode->slug }}</code>
                        </div>
                        @if($episode->thumbnail)
                            <div>
                                <span class="font-medium text-gray-900">Thumbnail:</span>
                                <span class="text-gray-700">{{ basename($episode->thumbnail) }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="font-medium text-gray-900">Status:</span>
                            <span class="text-gray-700">{{ $episode->is_published ? 'Published' : 'Draft' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Section -->
    @if($episode->is_published)
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-external-link-alt text-blue-600 mr-2"></i>
                <span class="text-blue-800 font-medium">This episode is published and visible to the public.</span>
            </div>
            <p class="text-blue-700 text-sm mt-1">
                Future feature: View public episode page at <code>/episodes/{{ $episode->slug }}</code>
            </p>
        </div>
    @else
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-eye-slash text-yellow-600 mr-2"></i>
                <span class="text-yellow-800 font-medium">This episode is saved as a draft.</span>
            </div>
            <p class="text-yellow-700 text-sm mt-1">
                It's not visible to the public until you publish it.
            </p>
        </div>
    @endif
</div>
@endsection
