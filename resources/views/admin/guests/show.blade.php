@extends('admin.layout')

@section('title', 'Guest Details - ' . $guest->name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.guests.index') }}" 
               class="text-gray-600 hover:text-gray-900 mr-4 transition-colors duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $guest->name }}</h1>
                <p class="text-gray-600 mt-2">Guest Profile Details</p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('admin.guests.edit', $guest) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Edit Guest
            </a>
            
            <form method="POST" 
                  action="{{ route('admin.guests.toggle-featured', $guest) }}" 
                  class="inline">
                @csrf
                <button type="submit" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-star mr-2"></i>{{ $guest->is_featured ? 'Unfeature' : 'Feature' }}
                </button>
            </form>
            
            <form method="POST" 
                  action="{{ route('admin.guests.destroy', $guest) }}" 
                  class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this guest? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Name</label>
                        <p class="text-gray-900">{{ $guest->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">URL Slug</label>
                        <p class="text-gray-900 font-mono">{{ $guest->slug }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $guest->sort_order }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <div class="flex items-center space-x-2">
                            @if($guest->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Regular
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Short Description</label>
                        <p class="text-gray-900">{{ $guest->short_description ?: 'No short description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Full Biography -->
            @if($guest->description)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Biography</h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $guest->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Episodes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Episodes ({{ $guest->episodes->count() }})</h3>
                </div>
                
                @if($guest->episodes->count() > 0)
                    <div class="space-y-4">
                        @foreach($guest->episodes as $episode)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    @if($episode->thumbnail_url)
                                        <img src="{{ $episode->thumbnail_url }}" 
                                             alt="{{ $episode->title }}" 
                                             class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-podcast text-gray-400"></i>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $episode->title }}</h4>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-sm text-gray-500">
                                                Order: {{ $episode->pivot->sort_order }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $episode->pivot->is_host ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $episode->pivot->is_host ? 'Host' : 'Guest' }}
                                            </span>
                                            @if($episode->is_published)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Draft
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.episodes.show', $episode) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                       title="View Episode">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($episode->is_published)
                                        <a href="{{ route('episodes.show', $episode) }}" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                           title="View Public Page">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-podcast text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">This guest hasn't appeared in any episodes yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Photo -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h3>
                
                <div class="text-center">
                    @if($guest->photo_url)
                        <img src="{{ $guest->photo_url }}" 
                             alt="{{ $guest->name }}" 
                             class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-gray-600 text-4xl"></i>
                        </div>
                    @endif
                    
                    @if(!$guest->photo_url)
                        <p class="text-gray-500 text-sm">No photo uploaded</p>
                    @endif
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Social Media & Links</h3>
                
                @if(!empty($guest->social_links))
                    <div class="space-y-3">
                        @foreach($guest->social_links as $platform => $link)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="{{ $link['icon'] }} text-gray-600"></i>
                                    <span class="text-gray-900">{{ $link['label'] }}</span>
                                </div>
                                <a href="{{ $link['url'] }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No social media links provided.</p>
                @endif
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Episodes</span>
                        <span class="font-semibold text-gray-900">{{ $guest->episode_count }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Published Episodes</span>
                        <span class="font-semibold text-gray-900">{{ $guest->published_episode_count }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Host Appearances</span>
                        <span class="font-semibold text-gray-900">{{ $guest->hostedEpisodes()->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Guest Appearances</span>
                        <span class="font-semibold text-gray-900">{{ $guest->episodes()->wherePivot('is_host', false)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="text-gray-900 block">{{ $guest->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="text-gray-900 block">{{ $guest->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-500">ID:</span>
                        <span class="text-gray-900 font-mono">{{ $guest->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
