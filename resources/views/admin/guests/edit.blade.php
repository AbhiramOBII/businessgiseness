@extends('admin.layout')

@section('title', 'Edit Guest - ' . $guest->name)

@push('head')
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.guests.index') }}" 
           class="text-gray-600 hover:text-gray-900 mr-4 transition-colors duration-200">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Guest</h1>
            <p class="text-gray-600 mt-2">Update {{ $guest->name }}'s profile</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.guests.update', $guest) }}" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Basic Information -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Guest Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $guest->name) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Slug
                            <span class="text-gray-500 text-xs">(auto-generated if empty)</span>
                        </label>
                        <input type="text" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug', $guest->slug) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', $guest->sort_order) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Short Description -->
                    <div class="md:col-span-2">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Short Description
                        </label>
                        <textarea id="short_description" 
                                  name="short_description" 
                                  rows="3"
                                  placeholder="Brief description or tagline for the guest..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('short_description') border-red-500 @enderror">{{ old('short_description', $guest->short_description) }}</textarea>
                        @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Biography
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="6"
                                  placeholder="Detailed biography, achievements, background..."
                                  class="w-full">{{ old('description', $guest->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Photo Upload -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h3>
                
                <div class="flex items-start space-x-6">
                    <!-- Photo Preview -->
                    <div class="flex-shrink-0">
                        <div id="photo-preview" class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden">
                            @if($guest->photo_url)
                                <img src="{{ $guest->photo_url }}" alt="{{ $guest->name }}" class="h-24 w-24 rounded-full object-cover">
                            @else
                                <i class="fas fa-user text-gray-600 text-2xl"></i>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Upload Controls -->
                    <div class="flex-1">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Photo
                        </label>
                        <input type="file" 
                               id="photo" 
                               name="photo" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photo') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF up to 2MB. Leave empty to keep current photo.</p>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Social Media & Links</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-globe mr-2"></i>Website
                        </label>
                        <input type="url" 
                               id="website" 
                               name="website" 
                               value="{{ old('website', $guest->website) }}"
                               placeholder="https://example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website') border-red-500 @enderror">
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Twitter -->
                    <div>
                        <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </label>
                        <input type="text" 
                               id="twitter" 
                               name="twitter" 
                               value="{{ old('twitter', $guest->twitter) }}"
                               placeholder="username or full URL"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter') border-red-500 @enderror">
                        @error('twitter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- LinkedIn -->
                    <div>
                        <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-linkedin mr-2"></i>LinkedIn
                        </label>
                        <input type="text" 
                               id="linkedin" 
                               name="linkedin" 
                               value="{{ old('linkedin', $guest->linkedin) }}"
                               placeholder="username or full URL"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('linkedin') border-red-500 @enderror">
                        @error('linkedin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-instagram mr-2"></i>Instagram
                        </label>
                        <input type="text" 
                               id="instagram" 
                               name="instagram" 
                               value="{{ old('instagram', $guest->instagram) }}"
                               placeholder="username or full URL"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('instagram') border-red-500 @enderror">
                        @error('instagram')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Episode Association -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Episode Association</h3>
                
                <div id="episode-associations">
                    @if($guest->episodes->count() > 0)
                        @foreach($guest->episodes as $episode)
                            <div class="episode-association-item mb-4 p-4 border border-gray-200 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Episode Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Episode</label>
                                        <select name="episodes[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Select Episode</option>
                                            @foreach($episodes as $ep)
                                                <option value="{{ $ep->id }}" {{ $ep->id == $episode->id ? 'selected' : '' }}>{{ $ep->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Role -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                        <select name="episode_roles[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="guest" {{ !$episode->pivot->is_host ? 'selected' : '' }}>Guest</option>
                                            <option value="host" {{ $episode->pivot->is_host ? 'selected' : '' }}>Host</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Order -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                                        <input type="number" name="episode_orders[]" value="{{ $episode->pivot->sort_order }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                                <button type="button" class="remove-episode-association mt-2 text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="episode-association-item mb-4 p-4 border border-gray-200 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Episode Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Episode</label>
                                    <select name="episodes[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Episode</option>
                                        @foreach($episodes as $episode)
                                            <option value="{{ $episode->id }}">{{ $episode->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Role -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <select name="episode_roles[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="guest">Guest</option>
                                        <option value="host">Host</option>
                                    </select>
                                </div>
                                
                                <!-- Order -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                                    <input type="number" name="episode_orders[]" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            <button type="button" class="remove-episode-association mt-2 text-red-600 hover:text-red-800 text-sm">
                                <i class="fas fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                    @endif
                </div>
                
                <button type="button" id="add-episode-association" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i>Add Another Episode
                </button>
            </div>

            <!-- Settings -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_featured" 
                           name="is_featured" 
                           value="1"
                           {{ old('is_featured', $guest->is_featured) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                        Featured Guest
                        <span class="text-gray-500 text-xs block">Featured guests appear prominently on the site</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.guests.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>Update Guest
            </button>
        </div>
    </form>
</div>

<script>
// Photo preview
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photo-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="h-24 w-24 rounded-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }
});

// Episode association management
document.getElementById('add-episode-association').addEventListener('click', function() {
    const container = document.getElementById('episode-associations');
    const template = container.querySelector('.episode-association-item').cloneNode(true);
    
    // Clear values
    template.querySelectorAll('select, input').forEach(field => {
        field.value = field.type === 'number' ? '0' : '';
    });
    
    container.appendChild(template);
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-episode-association')) {
        const item = e.target.closest('.episode-association-item');
        if (document.querySelectorAll('.episode-association-item').length > 1) {
            item.remove();
        }
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
            writer.setStyle('min-height', '300px', editor.editing.view.document.getRoot());
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
