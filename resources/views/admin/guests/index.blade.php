@extends('admin.layout')

@section('title', 'Manage Guests')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Guests</h1>
            <p class="text-gray-600 mt-2">Manage podcast guests and their information</p>
        </div>
        <a href="{{ route('admin.guests.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Add New Guest
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.guests.index') }}" class="flex flex-wrap gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by name or description..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Featured Filter -->
            <div class="min-w-48">
                <label for="featured" class="block text-sm font-medium text-gray-700 mb-2">Featured Status</label>
                <select id="featured" 
                        name="featured" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Guests</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>

            <!-- Sort -->
            <div class="min-w-48">
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sort" 
                        name="sort" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="sort_order" {{ request('sort') === 'sort_order' ? 'selected' : '' }}>Sort Order</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                    <option value="episode_count" {{ request('sort') === 'episode_count' ? 'selected' : '' }}>Episode Count</option>
                </select>
            </div>

            <!-- Sort Direction -->
            <div class="min-w-32">
                <label for="direction" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                <select id="direction" 
                        name="direction" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.guests.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Guests Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($guests->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Episodes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Social Media</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($guests as $guest)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($guest->photo_url)
                                                <img class="h-12 w-12 rounded-full object-cover" 
                                                     src="{{ $guest->photo_url }}" 
                                                     alt="{{ $guest->name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $guest->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($guest->short_description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $guest->episodes_count ?? $guest->episode_count }} episodes</div>
                                    <div class="text-sm text-gray-500">{{ $guest->published_episode_count }} published</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        @if($guest->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500">Order: {{ $guest->sort_order }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @foreach($guest->social_links as $platform => $link)
                                            <a href="{{ $link['url'] }}" 
                                               target="_blank" 
                                               class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                               title="{{ $link['label'] }}">
                                                <i class="{{ $link['icon'] }}"></i>
                                            </a>
                                        @endforeach
                                        @if(empty($guest->social_links))
                                            <span class="text-gray-400 text-sm">None</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $guest->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View -->
                                        <a href="{{ route('admin.guests.show', $guest) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                           title="View Guest">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit -->
                                        <a href="{{ route('admin.guests.edit', $guest) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                           title="Edit Guest">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Toggle Featured -->
                                        <form method="POST" 
                                              action="{{ route('admin.guests.toggle-featured', $guest) }}" 
                                              class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                    title="{{ $guest->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                <i class="fas fa-star{{ $guest->is_featured ? '' : '-o' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Delete -->
                                        <form method="POST" 
                                              action="{{ route('admin.guests.destroy', $guest) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this guest? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    title="Delete Guest">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $guests->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No guests found</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request()->hasAny(['search', 'featured', 'sort']))
                            No guests match your current filters. Try adjusting your search criteria.
                        @else
                            Get started by adding your first podcast guest.
                        @endif
                    </p>
                    <a href="{{ route('admin.guests.create') }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Add First Guest
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
