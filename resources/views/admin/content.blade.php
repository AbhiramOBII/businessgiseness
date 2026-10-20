@extends('admin.layout')

@section('title', 'Content Management')
@section('page-title', 'Content Management')
@section('page-description', 'Edit hero content, site settings and social links')

@section('content')
<div class="space-y-8">

    {{-- ── Live YouTube stats ─────────────────────────────────────── --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fab fa-youtube text-red-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Live YouTube Stats</h3>
                    <p class="text-xs text-gray-500">
                        Subscribers and views from both channels, refreshed automatically every few hours
                        @if($youtube) · last updated {{ \Illuminate\Support\Carbon::parse($youtube['synced_at'])->diffForHumans() }} @endif
                    </p>
                </div>
            </div>
            @if($youtubeKeySet)
                <form action="{{ route('admin.content.youtube-sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> Sync now
                    </button>
                </form>
            @endif
        </div>

        <div class="p-6">
            @if(session('success_youtube'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success_youtube') }}
                </div>
            @endif
            @if(session('error_youtube'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error_youtube') }}
                </div>
            @endif

            @if(!$youtubeKeySet)
                <div class="px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm">
                    <i class="fas fa-key"></i>
                    Live stats are off. Add a <strong>YouTube Data API v3</strong> key as <code class="bg-white px-1 rounded">YOUTUBE_API_KEY</code> in the server's <code class="bg-white px-1 rounded">.env</code> file to enable them. Until then, the hero stats below are shown.
                </div>
            @elseif($youtube)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-lg bg-gray-900 text-white p-4">
                        <p class="text-xs uppercase tracking-wider text-gray-400">Total</p>
                        <p class="mt-1 text-2xl font-bold">{{ number_format($youtube['totals']['subscribers']) }} <span class="text-sm font-normal text-gray-400">subscribers</span></p>
                        <p class="text-sm text-gray-300">{{ number_format($youtube['totals']['views']) }} views</p>
                    </div>
                    @foreach($youtube['channels'] as $channel)
                        <div class="rounded-lg border border-gray-200 p-4">
                            <p class="text-xs text-gray-500 truncate" title="{{ $channel['title'] }}">{{ $channel['title'] }} · {{ $channel['handle'] }}</p>
                            <p class="mt-1 text-xl font-bold text-gray-900">{{ $channel['subscribers'] !== null ? number_format($channel['subscribers']) : 'Hidden' }} <span class="text-sm font-normal text-gray-500">subscribers</span></p>
                            <p class="text-sm text-gray-600">{{ number_format($channel['views']) }} views · {{ $channel['videos'] }} videos</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-600">No stats fetched yet — click <strong>Sync now</strong>.</p>
            @endif
        </div>
    </div>

    {{-- ── 1. Home Hero ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Home Hero Section</h3>
                    <p class="text-xs text-gray-500">Headline, subtitle and stats shown on the homepage banner</p>
                </div>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                <i class="fas fa-external-link-alt"></i> Preview
            </a>
        </div>

        @if(session('success_hero'))
            <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success_hero') }}
            </div>
        @endif

        <form action="{{ route('admin.content.hero') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Headline Lines --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Headline</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Line 1</label>
                        <input type="text" name="hero_title_line1"
                               value="{{ old('hero_title_line1', $hero['hero_title_line1'] ?? 'Founder Stories') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none @error('hero_title_line1') border-red-400 @enderror">
                        @error('hero_title_line1')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Line 2</label>
                        <input type="text" name="hero_title_line2"
                               value="{{ old('hero_title_line2', $hero['hero_title_line2'] ?? 'That Deserve to') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none @error('hero_title_line2') border-red-400 @enderror">
                        @error('hero_title_line2')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Line 3 <span class="text-yellow-500">(gold accent)</span></label>
                        <input type="text" name="hero_title_line3_gold"
                               value="{{ old('hero_title_line3_gold', $hero['hero_title_line3_gold'] ?? 'Be Heard') }}"
                               class="w-full border border-yellow-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none @error('hero_title_line3_gold') border-red-400 @enderror">
                        @error('hero_title_line3_gold')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Badge + Subtitle --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Badge Text</label>
                    <input type="text" name="hero_badge_text"
                           value="{{ old('hero_badge_text', $hero['hero_badge_text'] ?? 'Now Streaming') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">Subtitle</label>
                    <textarea name="hero_subtitle" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none resize-none @error('hero_subtitle') border-red-400 @enderror">{{ old('hero_subtitle', $hero['hero_subtitle'] ?? '') }}</textarea>
                    @error('hero_subtitle')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Stats --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Stats</h4>
                @if($youtube)
                    <p class="text-xs text-gray-500 mb-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Live YouTube numbers are shown on the site (see "Live YouTube Stats" below). These values are only used if live stats are unavailable.
                    </p>
                @endif
                <div class="grid grid-cols-3 gap-4">
                    @foreach([1,2,3] as $i)
                    <div class="border border-gray-200 rounded-lg p-3 space-y-2">
                        <p class="text-xs text-gray-500 font-medium">Stat {{ $i }}</p>
                        <input type="text" name="hero_stat_{{ $i }}_value"
                               value="{{ old("hero_stat_{$i}_value", $hero["hero_stat_{$i}_value"] ?? '') }}"
                               placeholder="e.g. 23"
                               class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm font-bold text-center focus:ring-2 focus:ring-yellow-400 outline-none">
                        <input type="text" name="hero_stat_{{ $i }}_label"
                               value="{{ old("hero_stat_{$i}_label", $hero["hero_stat_{$i}_label"] ?? '') }}"
                               placeholder="e.g. Episodes"
                               class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs text-center focus:ring-2 focus:ring-yellow-400 outline-none">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-6 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Hero
                </button>
            </div>
        </form>
    </div>

    {{-- ── 2. Site Settings ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cog text-blue-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Site Settings</h3>
                    <p class="text-xs text-gray-500">Site title, description, contact email and phone number</p>
                </div>
            </div>
        </div>

        @if(session('success_site'))
            <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success_site') }}
            </div>
        @endif

        <form action="{{ route('admin.content.site') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Site Title</label>
                    <input type="text" name="site_title"
                           value="{{ old('site_title', $site['site_title'] ?? 'Business Giseness') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none @error('site_title') border-red-400 @enderror">
                    @error('site_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tagline</label>
                    <input type="text" name="site_tagline"
                           value="{{ old('site_tagline', $site['site_tagline'] ?? 'Founder-First Stories') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Site Description <span class="text-gray-400">(used in footer and SEO)</span></label>
                <textarea name="site_description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none @error('site_description') border-red-400 @enderror">{{ old('site_description', $site['site_description'] ?? '') }}</textarea>
                @error('site_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contact Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="email" name="contact_email"
                               value="{{ old('contact_email', $site['contact_email'] ?? '') }}"
                               class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none @error('contact_email') border-red-400 @enderror">
                    </div>
                    @error('contact_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contact Phone</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="contact_phone"
                               value="{{ old('contact_phone', $site['contact_phone'] ?? '') }}"
                               class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none @error('contact_phone') border-red-400 @enderror">
                    </div>
                    @error('contact_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Site Settings
                </button>
            </div>
        </form>
    </div>

    {{-- ── 3. Social Media Links ────────────────────────────────────── --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-share-alt text-green-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Social Media Links</h3>
                    <p class="text-xs text-gray-500">URLs used in the footer, header and across the site</p>
                </div>
            </div>
        </div>

        @if(session('success_social'))
            <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success_social') }}
            </div>
        @endif

        <form action="{{ route('admin.content.social') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1 flex items-center gap-2">
                        <i class="fab fa-youtube text-red-500"></i> YouTube URL — Kannada channel
                    </label>
                    <input type="url" name="social_youtube"
                           value="{{ old('social_youtube', $social['social_youtube'] ?? '') }}"
                           placeholder="https://www.youtube.com/@..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-300 outline-none @error('social_youtube') border-red-400 @enderror">
                    @error('social_youtube')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1 flex items-center gap-2">
                        <i class="fab fa-youtube text-red-500"></i> YouTube URL — English channel
                    </label>
                    <input type="url" name="social_youtube_english"
                           value="{{ old('social_youtube_english', $social['social_youtube_english'] ?? 'https://www.youtube.com/@BusinessGisenessEnglish') }}"
                           placeholder="https://www.youtube.com/@..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-300 outline-none @error('social_youtube_english') border-red-400 @enderror">
                    @error('social_youtube_english')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1 flex items-center gap-2">
                        <i class="fab fa-spotify text-green-500"></i> Spotify URL
                    </label>
                    <input type="url" name="social_spotify"
                           value="{{ old('social_spotify', $social['social_spotify'] ?? '') }}"
                           placeholder="https://creators.spotify.com/..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-300 outline-none @error('social_spotify') border-red-400 @enderror">
                    @error('social_spotify')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1 flex items-center gap-2">
                        <i class="fab fa-instagram text-pink-500"></i> Instagram URL
                    </label>
                    <input type="url" name="social_instagram"
                           value="{{ old('social_instagram', $social['social_instagram'] ?? '') }}"
                           placeholder="https://www.instagram.com/..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-300 outline-none @error('social_instagram') border-red-400 @enderror">
                    @error('social_instagram')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1 flex items-center gap-2">
                        <i class="fab fa-linkedin text-blue-600"></i> LinkedIn URL
                    </label>
                    <input type="url" name="social_linkedin"
                           value="{{ old('social_linkedin', $social['social_linkedin'] ?? '') }}"
                           placeholder="https://www.linkedin.com/company/..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 outline-none @error('social_linkedin') border-red-400 @enderror">
                    @error('social_linkedin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Social Links
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
