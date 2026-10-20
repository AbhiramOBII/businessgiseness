@extends('admin.layout')

@php $editing = $event->exists; @endphp

@section('title', $editing ? 'Edit Meetup' : 'New Meetup')
@section('page-title', $editing ? 'Edit Meetup' : 'New BG Community Meetup')
@section('page-description', 'Set up a networking meetup members can sign up for')

@section('content')
@php
    $input = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold outline-none';
    $label = 'block text-sm font-medium text-gray-700 mb-2';
    $err   = 'text-red-600 text-sm mt-1';
@endphp
<div class="max-w-4xl" x-data="{ online: {{ old('is_online', $event->is_online) ? 'true' : 'false' }} }">
    <div class="mb-6">
        <a href="{{ $editing ? route('admin.community-events.show', $event) : route('admin.community-events.index') }}"
           class="text-gray-600 hover:text-gray-800 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <form action="{{ $editing ? route('admin.community-events.update', $event) : route('admin.community-events.store') }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-8">
        @csrf
        @if($editing) @method('PUT') @endif

        {{-- Basics --}}
        <section class="space-y-5">
            <h3 class="text-lg font-semibold text-gray-900">Meetup details</h3>
            <div>
                <label for="title" class="{{ $label }}">Title *</label>
                <input id="title" name="title" type="text" required value="{{ old('title', $event->title) }}" class="{{ $input }}"
                       placeholder="e.g. BG Community Meetup — Bengaluru, November">
                @error('title')<p class="{{ $err }}">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="summary" class="{{ $label }}">Short summary * <span class="text-gray-400 font-normal">(shown on the listing, max 300 characters)</span></label>
                <textarea id="summary" name="summary" rows="2" maxlength="300" required class="{{ $input }}"
                          placeholder="An evening for founders and MSME owners to meet potential customers, vendors and partners.">{{ old('summary', $event->summary) }}</textarea>
                @error('summary')<p class="{{ $err }}">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="description" class="{{ $label }}">Full description <span class="text-gray-400 font-normal">(agenda, who should attend — line breaks are kept)</span></label>
                <textarea id="description" name="description" rows="8" class="{{ $input }}">{{ old('description', $event->description) }}</textarea>
                @error('description')<p class="{{ $err }}">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- When & where --}}
        <section class="space-y-5 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900">When &amp; where</h3>
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="starts_at" class="{{ $label }}">Starts *</label>
                    <input id="starts_at" name="starts_at" type="datetime-local" required class="{{ $input }}"
                           value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\TH:i')) }}">
                    @error('starts_at')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ends_at" class="{{ $label }}">Ends</label>
                    <input id="ends_at" name="ends_at" type="datetime-local" class="{{ $input }}"
                           value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\TH:i')) }}">
                    @error('ends_at')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_online" value="0">
                <input type="checkbox" name="is_online" value="1" x-model="online" class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                Online meetup <span class="text-gray-400">(share the joining link with members by email)</span>
            </label>

            <div class="grid md:grid-cols-2 gap-5" x-show="!online">
                <div>
                    <label for="venue_name" class="{{ $label }}">Venue *</label>
                    <input id="venue_name" name="venue_name" type="text" class="{{ $input }}" value="{{ old('venue_name', $event->venue_name) }}"
                           placeholder="e.g. Obii Studios">
                    @error('venue_name')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="venue_address" class="{{ $label }}">Address</label>
                    <input id="venue_address" name="venue_address" type="text" class="{{ $input }}" value="{{ old('venue_address', $event->venue_address) }}"
                           placeholder="Area, Bengaluru">
                    @error('venue_address')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="map_link" class="{{ $label }}">Google Maps link</label>
                    <input id="map_link" name="map_link" type="url" class="{{ $input }}" value="{{ old('map_link', $event->map_link) }}" placeholder="https://maps.app.goo.gl/...">
                    @error('map_link')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        {{-- Sign-ups --}}
        <section class="space-y-5 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900">Sign-ups</h3>
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="capacity" class="{{ $label }}">Seats <span class="text-gray-400 font-normal">(leave empty for no limit)</span></label>
                    <input id="capacity" name="capacity" type="number" min="1" class="{{ $input }}" value="{{ old('capacity', $event->capacity) }}" placeholder="e.g. 40">
                    @error('capacity')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="cover_image" class="{{ $label }}">Cover image <span class="text-gray-400 font-normal">(optional, used when the link is shared)</span></label>
                    <input id="cover_image" name="cover_image" type="file" accept="image/*" class="block w-full text-sm text-gray-600">
                    @error('cover_image')<p class="{{ $err }}">{{ $message }}</p>@enderror
                    @if($event->cover_url)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ $event->cover_url }}" alt="" class="h-16 rounded">
                            <label class="text-sm text-gray-600"><input type="checkbox" name="remove_cover_image" value="1" class="rounded border-gray-300"> Remove</label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="registration_open" value="0">
                    <input type="checkbox" name="registration_open" value="1" @checked(old('registration_open', $event->registration_open)) class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    Accept sign-ups
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $event->is_published)) class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    Published <span class="text-gray-400">(visible on the Community page)</span>
                </label>
            </div>

            <div>
                <label for="slug" class="{{ $label }}">URL slug <span class="text-gray-400 font-normal">(optional — generated from the title)</span></label>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-1">{{ route('community.index') }}/</span>
                    <input id="slug" name="slug" type="text" class="{{ $input }}" value="{{ old('slug', $event->slug) }}">
                </div>
                @error('slug')<p class="{{ $err }}">{{ $message }}</p>@enderror
            </div>
        </section>

        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
            @if($editing)
                <span></span>
            @else
                <span class="text-sm text-gray-500">You can share the sign-up link once it's published.</span>
            @endif
            <button type="submit" class="bg-brand-gold hover:bg-opacity-90 text-brand-dark px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>{{ $editing ? 'Save changes' : 'Create meetup' }}
            </button>
        </div>
    </form>

    @if($editing)
        <form action="{{ route('admin.community-events.destroy', $event) }}" method="POST" class="mt-6 text-right"
              onsubmit="return confirm('Delete this meetup and all {{ $event->registrations()->count() }} sign-ups? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-800"><i class="fas fa-trash mr-1"></i>Delete meetup</button>
        </form>
    @endif
</div>
@endsection
