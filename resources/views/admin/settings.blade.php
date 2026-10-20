@extends('admin.layout')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-description', 'Manage SEO, account password and general configuration')

@section('content')

@php
    $activeTab = session()->has('success_password') ? 'account' : (session()->has('success_general') ? 'general' : 'seo');
@endphp

<div x-data="{ tab: '{{ $activeTab }}' }">

    {{-- Tab Bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-200 px-6">
            <button @click="tab = 'seo'"
                :class="tab === 'seo' ? 'border-brand-gold text-brand-gold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-4 border-b-2 font-semibold text-sm transition-colors -mb-px">
                <i class="fas fa-search-dollar w-4"></i> SEO Settings
            </button>
            <button @click="tab = 'account'"
                :class="tab === 'account' ? 'border-brand-gold text-brand-gold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-4 border-b-2 font-semibold text-sm transition-colors -mb-px">
                <i class="fas fa-lock w-4"></i> Account & Password
            </button>
            <button @click="tab = 'general'"
                :class="tab === 'general' ? 'border-brand-gold text-brand-gold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-4 border-b-2 font-semibold text-sm transition-colors -mb-px">
                <i class="fas fa-cog w-4"></i> General
            </button>
        </div>
    </div>

    {{-- ── SEO TAB ───────────────────────────────────────────── --}}
    <div x-show="tab === 'seo'" x-cloak>

        @if(session('success_seo'))
            <div class="mb-5 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success_seo') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.seo') }}">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- Default Meta --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heading text-brand-gold text-xs"></i>
                        </span>
                        Default Meta Tags
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Site Title <span class="text-gray-400 font-normal text-xs">(max 60 chars)</span></label>
                            <input type="text" name="seo_site_title" maxlength="100"
                                value="{{ old('seo_site_title', $seo['seo_site_title'] ?? 'Business Giseness - Founder-First Storytelling Podcast') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                            @error('seo_site_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Meta Description <span class="text-gray-400 font-normal text-xs">(max 160 chars)</span></label>
                            <textarea name="seo_meta_description" rows="3" maxlength="300"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition resize-none">{{ old('seo_meta_description', $seo['seo_meta_description'] ?? '') }}</textarea>
                            @error('seo_meta_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Robots Meta</label>
                            <select name="seo_robots" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                                @foreach(['index, follow', 'noindex, follow', 'index, nofollow', 'noindex, nofollow'] as $opt)
                                    <option value="{{ $opt }}" {{ ($seo['seo_robots'] ?? 'index, follow') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default OG Image URL <span class="text-gray-400 font-normal text-xs">(1200×630 px)</span></label>
                            <input type="url" name="seo_default_og_image_url"
                                value="{{ old('seo_default_og_image_url', $seo['seo_default_og_image_url'] ?? '') }}"
                                placeholder="https://yourdomain.com/images/og-banner.jpg"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                            @error('seo_default_og_image_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-400 mt-1">Fallback image for social shares when no page-specific image exists.</p>
                        </div>
                    </div>
                </div>

                {{-- Google Tools --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                                <i class="fab fa-google text-brand-gold text-xs"></i>
                            </span>
                            Google Tools
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">GA4 Measurement ID</label>
                                <input type="text" name="seo_ga4_id"
                                    value="{{ old('seo_ga4_id', $seo['seo_ga4_id'] ?? '') }}"
                                    placeholder="G-XXXXXXXXXX"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-mono focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                                <p class="text-xs text-gray-400 mt-1">Automatically injected into every page when set.</p>
                                @error('seo_ga4_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Search Console Verification</label>
                                <input type="text" name="seo_gsc_verification"
                                    value="{{ old('seo_gsc_verification', $seo['seo_gsc_verification'] ?? '') }}"
                                    placeholder="Paste the content=&quot;...&quot; value only"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-mono focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                                <p class="text-xs text-gray-400 mt-1">Only the value inside <code class="bg-gray-100 px-1 rounded">content="..."</code> from the GSC meta tag.</p>
                                @error('seo_gsc_verification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sitemap Stats --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sitemap text-brand-gold text-xs"></i>
                            </span>
                            Sitemap Status
                        </h3>
                        <div class="space-y-2">
                            @php
                                $urlCount = 8 + \App\Models\Episode::published()->count() + \App\Models\Guest::count() + \App\Models\BlogPost::published()->count();
                            @endphp
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">
                                <span class="text-sm text-gray-600">Total URLs indexed</span>
                                <span class="font-semibold text-gray-800 text-sm">{{ $urlCount }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">
                                <span class="text-sm text-gray-600">Sitemap</span>
                                <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-sm text-brand-gold hover:underline font-mono">View XML</a>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">
                                <span class="text-sm text-gray-600">robots.txt</span>
                                <a href="{{ url('/robots.txt') }}" target="_blank" class="text-sm text-brand-gold hover:underline font-mono">View</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold hover:bg-yellow-500 text-brand-dark font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    <i class="fas fa-save"></i> Save SEO Settings
                </button>
            </div>
        </form>
    </div>

    {{-- ── ACCOUNT TAB ───────────────────────────────────────── --}}
    <div x-show="tab === 'account'" x-cloak>

        @if(session('success_password'))
            <div class="mb-5 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success_password') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Account Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-circle text-brand-gold text-xs"></i>
                    </span>
                    Account Info
                </h3>
                <div class="space-y-1">
                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Name</span>
                        <span class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Email</span>
                        <span class="text-sm font-medium text-gray-800">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Role</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-brand-gold text-brand-dark">
                            Administrator
                        </span>
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-brand-gold text-xs"></i>
                    </span>
                    Change Password
                </h3>
                <form method="POST" action="{{ route('admin.settings.password') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" autocomplete="current-password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition @error('current_password') border-red-400 @enderror">
                            @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                            <input type="password" name="password" autocomplete="new-password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition @error('password') border-red-400 @enderror">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-400 mt-1">Min 8 characters, mixed case and numbers.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                        </div>
                        <div class="pt-1">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-brand-dark hover:bg-gray-800 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- ── GENERAL TAB ───────────────────────────────────────── --}}
    <div x-show="tab === 'general'" x-cloak>

        @if(session('success_general'))
            <div class="mb-5 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success_general') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.general') }}">
            @csrf
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                            <i class="fas fa-globe text-brand-gold text-xs"></i>
                        </span>
                        Domain & Sitemap
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Production Domain</label>
                        <input type="url" name="general_sitemap_domain"
                            value="{{ old('general_sitemap_domain', $general['general_sitemap_domain'] ?? 'https://www.businessgiseness.com') }}"
                            placeholder="https://www.businessgiseness.com"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-mono focus:ring-2 focus:ring-brand-gold focus:border-transparent outline-none transition">
                        @error('general_sitemap_domain')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-400 mt-1">Used in the robots.txt Sitemap directive. No trailing slash.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <span class="w-7 h-7 bg-brand-gold bg-opacity-15 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-brand-gold text-xs"></i>
                        </span>
                        System Info
                    </h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between py-3 border-b border-gray-50">
                            <span class="text-gray-500">Laravel Version</span>
                            <span class="font-mono font-medium text-gray-800">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-50">
                            <span class="text-gray-500">PHP Version</span>
                            <span class="font-mono font-medium text-gray-800">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500">Environment</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ app()->isProduction() ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ app()->environment() }}
                            </span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-gray-500">Cache Driver</span>
                            <span class="font-mono font-medium text-gray-800">{{ config('cache.default') }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold hover:bg-yellow-500 text-brand-dark font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    <i class="fas fa-save"></i> Save General Settings
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
