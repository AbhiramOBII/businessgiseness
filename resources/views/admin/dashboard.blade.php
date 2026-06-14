@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your website statistics and recent activity')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Total Users</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Episodes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-podcast text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Total Episodes</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_episodes'] }}</p>
                </div>
            </div>
        </div>

        <!-- Published Episodes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Published</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['published_episodes'] }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-brand-gold bg-opacity-20 text-brand-gold">
                    <i class="fas fa-bolt text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                    <div class="mt-2 space-y-2">
                        <a href="{{ route('admin.episodes.create') }}" class="block text-sm text-brand-gold hover:text-yellow-700">
                            <i class="fas fa-plus mr-1"></i> New Episode
                        </a>
                        <a href="{{ route('admin.content') }}" class="block text-sm text-brand-gold hover:text-yellow-700">
                            <i class="fas fa-edit mr-1"></i> Edit Content
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Episodes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Recent Episodes</h3>
                <a href="{{ route('admin.episodes.index') }}" class="text-sm text-brand-gold hover:text-yellow-700">View All</a>
            </div>
            <div class="p-6">
                @if($stats['recent_episodes']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_episodes'] as $episode)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($episode->thumbnail)
                                        <img src="{{ $episode->thumbnail_url }}" 
                                             alt="{{ $episode->title }}" 
                                             class="w-10 h-10 object-cover rounded">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-podcast text-gray-400 text-sm"></i>
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($episode->title, 30) }}</p>
                                        <div class="flex items-center space-x-2">
                                            @if($episode->is_published)
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Published</span>
                                            @else
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Draft</span>
                                            @endif
                                            @if($episode->youtube_link)
                                                <i class="fab fa-youtube text-red-600 text-xs" title="Has YouTube link"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ $episode->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-podcast text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 mb-3">No episodes created yet</p>
                        <a href="{{ route('admin.episodes.create') }}" 
                           class="text-brand-gold hover:text-yellow-700 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>Create your first episode
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Users</h3>
            </div>
            <div class="p-6">
                @if($stats['recent_logins']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_logins'] as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-brand-gold rounded-full flex items-center justify-center">
                                        <span class="text-xs font-semibold text-brand-dark">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ $user->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No users registered yet</p>
                @endif
            </div>
        </div>

        <!-- Website Overview -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Website Overview</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-home text-blue-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Home Page</span>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            <span class="text-sm text-gray-700">About Page</span>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-yellow-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Authentication</span>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Enabled</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Admin Panel</span>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg shadow p-6 text-white">
        <div class="flex items-center">
            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                <i class="fas fa-rocket text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-semibold">Welcome to Business Giseness Admin Panel!</h3>
                <p class="mt-2 text-yellow-100">
                    You can manage your website content, view statistics, and monitor user activity from this dashboard. 
                    Start by exploring the content management section to customize your website.
                </p>
                <div class="mt-4">
                    <a href="{{ route('admin.content') }}" class="inline-flex items-center px-4 py-2 bg-white text-yellow-600 rounded-lg hover:bg-yellow-50 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Manage Content
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
