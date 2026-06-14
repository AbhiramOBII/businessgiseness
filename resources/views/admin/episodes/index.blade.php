@extends('admin.layout')

@section('title', 'Episodes')
@section('page-title', 'Episodes')
@section('page-description', 'Manage your podcast episodes')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Episodes</h2>
            <p class="text-gray-600">Manage your podcast episodes</p>
        </div>
        <a href="{{ route('admin.episodes.create') }}" 
           class="bg-brand-gold hover:bg-brand-gold hover:bg-opacity-90 text-brand-dark px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Add New Episode
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Episodes Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($episodes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Episode</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Published</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Created</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($episodes as $episode)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        @if($episode->thumbnail)
                                            <img src="{{ $episode->thumbnail_url }}" 
                                                 alt="{{ $episode->title }}" 
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <h3 class="font-semibold text-gray-900">{{ $episode->title }}</h3>
                                                @if($episode->youtube_link)
                                                    <i class="fab fa-youtube text-red-600" title="Has YouTube link"></i>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">{{ Str::limit($episode->short_description, 60) }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Slug: {{ $episode->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($episode->is_published)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    {{ $episode->published_at ? $episode->published_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    {{ $episode->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.episodes.show', $episode) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.episodes.edit', $episode) }}" 
                                           class="text-brand-gold hover:text-yellow-600 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.episodes.destroy', $episode) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this episode?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    title="Delete">
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
            @if($episodes->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $episodes->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-podcast text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No episodes yet</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first episode.</p>
                <a href="{{ route('admin.episodes.create') }}" 
                   class="bg-brand-gold hover:bg-brand-gold hover:bg-opacity-90 text-brand-dark px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Create First Episode
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
