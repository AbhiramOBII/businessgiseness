@extends('admin.layout')

@section('title', 'Create Episode')
@section('page-title', 'Create New Episode')
@section('page-description', 'Add a new podcast episode')

@push('head')
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
@endpush

@section('content')
<div class="max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.episodes.index') }}" 
           class="text-gray-600 hover:text-gray-800 transition-colors duration-200 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Episodes
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.episodes.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Episode Title *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200"
                               placeholder="Enter episode title"
                               required>
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- YouTube Link -->
                    <div>
                        <label for="youtube_link" class="block text-sm font-medium text-gray-700 mb-2">YouTube Link</label>
                        <input type="url" 
                               id="youtube_link" 
                               name="youtube_link" 
                               value="{{ old('youtube_link') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200"
                               placeholder="https://www.youtube.com/watch?v=...">
                        <p class="text-sm text-gray-500 mt-1">Full YouTube URL for this episode</p>
                        @error('youtube_link')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Short Description -->
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                        <textarea id="short_description" 
                                  name="short_description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200"
                                  placeholder="Brief description for episode cards and previews (max 500 characters)"
                                  maxlength="500"
                                  required>{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Long Description -->
                    <div>
                        <label for="long_description" class="block text-sm font-medium text-gray-700 mb-2">Full Description *</label>
                        <textarea id="long_description" 
                                  name="long_description" 
                                  rows="8"
                                  class="w-full"
                                  placeholder="Detailed episode description, show notes, guest information, etc."
                                  required>{{ old('long_description') }}</textarea>
                        @error('long_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Media</h3>
                
                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Episode Thumbnail</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <input type="file" 
                                   id="thumbnail" 
                                   name="thumbnail" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200">
                            <p class="text-sm text-gray-500 mt-1">Recommended: 1280x720px, JPG/PNG/WebP, max 2MB</p>
                        </div>
                        <div id="thumbnail-preview" class="hidden">
                            <img id="preview-image" src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                    @error('thumbnail')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Meta Title -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               value="{{ old('meta_title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200"
                               placeholder="SEO title (leave blank to use episode title)"
                               maxlength="255">
                        @error('meta_title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200"
                                  placeholder="SEO description for search engines (max 500 characters)"
                                  maxlength="500">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Publishing Options -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Publishing Options</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Publish Status -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="is_published" 
                                   value="1"
                                   {{ old('is_published') ? 'checked' : '' }}
                                   class="w-4 h-4 text-brand-gold border-gray-300 rounded focus:ring-brand-gold">
                            <span class="text-sm font-medium text-gray-700">Publish immediately</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Uncheck to save as draft</p>
                    </div>

                    <!-- Publish Date -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" 
                               id="published_at" 
                               name="published_at" 
                               value="{{ old('published_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition-colors duration-200">
                        <p class="text-sm text-gray-500 mt-1">Leave blank to use current time when publishing</p>
                        @error('published_at')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.episodes.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-brand-gold hover:bg-brand-gold hover:bg-opacity-90 text-brand-dark px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Create Episode
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Thumbnail preview
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('thumbnail-preview');
    const previewImage = document.getElementById('preview-image');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

// Initialize CKEditor
ClassicEditor
    .create(document.querySelector('#long_description'), {
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
        window.longDescriptionEditor = editor;
        
        // Custom styling for the editor
        editor.editing.view.change(writer => {
            writer.setStyle('min-height', '300px', editor.editing.view.document.getRoot());
        });
        
        // Handle form submission - multiple approaches for reliability
        const form = document.querySelector('form');
        const textarea = document.querySelector('#long_description');
        
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
