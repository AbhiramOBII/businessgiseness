@extends('admin.layout')

@section('title', 'Edit Blog Post')

@push('head')
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Blog Post</h1>
            <p class="text-gray-600 mt-2">{{ $blogPost->title }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.blog.show', $blogPost) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                <i class="fas fa-eye mr-2"></i>
                View Post
            </a>
            <a href="{{ route('admin.blog.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Posts
            </a>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.blog.update', $blogPost) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                    
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $blogPost->title) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                               placeholder="Enter blog post title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug', $blogPost->slug) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                               placeholder="auto-generated-from-title">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <input type="text" 
                               id="category" 
                               name="category" 
                               value="{{ old('category', $blogPost->category) }}"
                               list="categories"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                               placeholder="Enter or select category">
                        <datalist id="categories">
                            @foreach($categories as $category)
                                <option value="{{ $category }}">
                            @endforeach
                        </datalist>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Short Description -->
                    <div class="mb-6">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                        <textarea id="short_description" 
                                  name="short_description" 
                                  rows="3" 
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('short_description') border-red-500 @enderror"
                                  placeholder="Brief description for previews and SEO">{{ old('short_description', $blogPost->short_description) }}</textarea>
                        @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Thumbnail -->
                    @if($blogPost->thumbnail_url)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Featured Image</label>
                            <img src="{{ $blogPost->thumbnail_url }}" 
                                 alt="{{ $blogPost->title }}" 
                                 class="w-64 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    @endif

                    <!-- Thumbnail -->
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $blogPost->thumbnail ? 'Replace Featured Image' : 'Featured Image' }}
                        </label>
                        <input type="file" 
                               id="thumbnail" 
                               name="thumbnail" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('thumbnail') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Recommended: 1200x630px, max 2MB</p>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Content</h2>
                    
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Blog Content *</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="20" 
                                  required
                                  class="w-full"
                                  placeholder="Write your blog post content here...">{{ old('description', $blogPost->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">SEO Settings</h2>
                    
                    <!-- Meta Title -->
                    <div class="mb-6">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               value="{{ old('meta_title', $blogPost->getOriginal('meta_title')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_title') border-red-500 @enderror"
                               placeholder="SEO title (defaults to post title)">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-6">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_description') border-red-500 @enderror"
                                  placeholder="SEO description (defaults to short description)">{{ old('meta_description', $blogPost->getOriginal('meta_description')) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div class="mb-6">
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" 
                               id="meta_keywords" 
                               name="meta_keywords" 
                               value="{{ old('meta_keywords', $blogPost->meta_keywords) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_keywords') border-red-500 @enderror"
                               placeholder="keyword1, keyword2, keyword3">
                        @error('meta_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Post Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Post Statistics</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Views:</span>
                            <span class="text-sm font-semibold">{{ number_format($blogPost->views_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Reading Time:</span>
                            <span class="text-sm font-semibold">{{ $blogPost->reading_time_text ?? 'Auto-calculated' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Created:</span>
                            <span class="text-sm font-semibold">{{ $blogPost->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($blogPost->published_at)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Published:</span>
                                <span class="text-sm font-semibold">{{ $blogPost->published_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Publishing</h2>
                    
                    <!-- Published Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_published" 
                                   value="1"
                                   {{ old('is_published', $blogPost->is_published) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm font-medium text-gray-700">Published</span>
                        </label>
                    </div>

                    <!-- Published Date -->
                    <div class="mb-6">
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" 
                               id="published_at" 
                               name="published_at" 
                               value="{{ old('published_at', $blogPost->published_at ? $blogPost->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('published_at') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to use current time when publishing</p>
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', $blogPost->sort_order) }}"
                               min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Social Media Preview -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Social Media</h2>
                    
                    <!-- Open Graph Title -->
                    <div class="mb-4">
                        <label for="og_title" class="block text-sm font-medium text-gray-700 mb-2">Facebook Title</label>
                        <input type="text" 
                               id="og_title" 
                               name="og_title" 
                               value="{{ old('og_title', $blogPost->getOriginal('og_title')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_title') border-red-500 @enderror"
                               placeholder="Defaults to post title">
                        @error('og_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Open Graph Description -->
                    <div class="mb-4">
                        <label for="og_description" class="block text-sm font-medium text-gray-700 mb-2">Facebook Description</label>
                        <textarea id="og_description" 
                                  name="og_description" 
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_description') border-red-500 @enderror"
                                  placeholder="Defaults to short description">{{ old('og_description', $blogPost->getOriginal('og_description')) }}</textarea>
                        @error('og_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Twitter Title -->
                    <div class="mb-4">
                        <label for="twitter_title" class="block text-sm font-medium text-gray-700 mb-2">Twitter Title</label>
                        <input type="text" 
                               id="twitter_title" 
                               name="twitter_title" 
                               value="{{ old('twitter_title', $blogPost->getOriginal('twitter_title')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_title') border-red-500 @enderror"
                               placeholder="Defaults to post title">
                        @error('twitter_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Twitter Description -->
                    <div class="mb-4">
                        <label for="twitter_description" class="block text-sm font-medium text-gray-700 mb-2">Twitter Description</label>
                        <textarea id="twitter_description" 
                                  name="twitter_description" 
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_description') border-red-500 @enderror"
                                  placeholder="Defaults to short description">{{ old('twitter_description', $blogPost->getOriginal('twitter_description')) }}</textarea>
                        @error('twitter_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            <a href="{{ route('admin.blog.show', $blogPost) }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                Cancel
            </a>
            
            <div class="flex space-x-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    Update Post
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from title (only if slug is empty)
document.getElementById('title').addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.value) {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        slugField.value = slug;
    }
});

// Initialize CKEditor
ClassicEditor
    .create(document.querySelector('#description'), {
        toolbar: {
            items: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'outdent',
                'indent',
                '|',
                'blockQuote',
                'insertTable',
                'undo',
                'redo',
                '|',
                'alignment',
                'fontSize',
                'fontColor',
                'fontBackgroundColor',
                '|',
                'code',
                'codeBlock',
                'horizontalLine',
                'specialCharacters'
            ]
        },
        language: 'en',
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        licenseKey: '',
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
            ]
        },
        fontSize: {
            options: [
                9,
                11,
                13,
                'default',
                17,
                19,
                21
            ]
        },
        alignment: {
            options: ['left', 'center', 'right', 'justify']
        },
        link: {
            decorators: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                toggleDownloadable: {
                    mode: 'manual',
                    label: 'Downloadable',
                    attributes: {
                        download: 'file'
                    }
                }
            }
        }
    })
    .then(editor => {
        // Store editor instance for form validation
        window.descriptionEditor = editor;
        
        // Custom styling for the editor
        editor.editing.view.change(writer => {
            writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
        });
        
        // Handle form submission - multiple approaches for reliability
        const form = document.querySelector('form');
        const textarea = document.querySelector('#description');
        
        // Method 1: Form submit event
        form.addEventListener('submit', function(e) {
            textarea.value = editor.getData();
        });
        
        // Method 2: Button click events
        const submitButtons = form.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                textarea.value = editor.getData();
            });
        });
        
        // Method 3: Periodic sync (backup)
        setInterval(function() {
            textarea.value = editor.getData();
        }, 5000); // Sync every 5 seconds
    })
    .catch(error => {
        console.error('CKEditor initialization error:', error);
    });
</script>
@endsection
