@extends('admin.layout')

@section('title', 'Blog Posts Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Blog Posts</h1>
            <p class="text-gray-600 mt-2">Manage your blog content and publications</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Create New Post
        </a>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.blog.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Posts</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by title, description, or category..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.blog.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                        Clear
                    </a>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Sort by:</label>
                    <select name="sort" onchange="this.form.submit()" class="px-3 py-1 border border-gray-300 rounded text-sm">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="published_at" {{ request('sort') === 'published_at' ? 'selected' : '' }}>Published Date</option>
                        <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title</option>
                        <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Views</option>
                    </select>
                    <select name="order" onchange="this.form.submit()" class="px-3 py-1 border border-gray-300 rounded text-sm">
                        <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Blog Posts Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($blogPosts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($blogPosts as $post)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($post->thumbnail_url)
                                            <img src="{{ $post->thumbnail_url }}" 
                                                 alt="{{ $post->title }}" 
                                                 class="w-16 h-16 object-cover rounded-lg mr-4">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $post->title }}</h3>
                                            <p class="text-sm text-gray-600">{{ Str::limit($post->short_description, 80) }}</p>
                                            @if($post->reading_time)
                                                <span class="inline-block mt-1 text-xs text-gray-500">
                                                    <i class="fas fa-clock mr-1"></i>{{ $post->reading_time_text }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $post->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($post->is_published)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-edit mr-1"></i>Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex items-center space-x-4">
                                        <span><i class="fas fa-eye mr-1"></i>{{ number_format($post->views_count) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div>Created: {{ $post->created_at->format('M d, Y') }}</div>
                                    @if($post->published_at)
                                        <div>Published: {{ $post->published_at->format('M d, Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View -->
                                        <a href="{{ route('admin.blog.show', $post) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                           title="View Post">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.blog.edit', $post) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200"
                                           title="Edit Post">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Toggle Published -->
                                        <form method="POST" action="{{ route('admin.blog.toggle-published', $post) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="{{ $post->is_published ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }} transition-colors duration-200"
                                                    title="{{ $post->is_published ? 'Unpublish' : 'Publish' }}">
                                                <i class="fas fa-{{ $post->is_published ? 'eye-slash' : 'check' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Duplicate -->
                                        <form method="POST" action="{{ route('admin.blog.duplicate', $post) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-purple-600 hover:text-purple-800 transition-colors duration-200"
                                                    title="Duplicate Post">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    title="Delete Post">
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
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $blogPosts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-blog text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No blog posts found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['search', 'category', 'status']))
                        No posts match your current filters. Try adjusting your search criteria.
                    @else
                        Get started by creating your first blog post.
                    @endif
                </p>
                <a href="{{ route('admin.blog.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Post
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
