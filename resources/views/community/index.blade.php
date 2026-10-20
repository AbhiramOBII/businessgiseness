@extends('layouts.app')

@php
    $pageTitle = 'BG Community — Business Networking for Founders & MSME Owners in Bengaluru | Business Giseness';
    $pageDescription = 'BG Community by Business Giseness connects business owners in Bengaluru and Karnataka with like-minded founders — meet potential customers, vendors and partners at our networking meetups.';
@endphp

@section('title', $pageTitle)
@section('meta-description', $pageDescription)
@section('meta_keywords', 'BG Community, business networking Bengaluru, MSME networking, founders meetup Bangalore, business owners community Karnataka, Business Giseness community')
@section('og_title', 'BG Community — Business networking by Business Giseness')
@section('og_description', $pageDescription)
@section('canonical_url', route('community.index'))

@push('head')
<script type="application/ld+json">{!! \App\Support\Seo::json([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'BG Community',
    'url' => route('community.index'),
    'description' => $pageDescription,
    'isPartOf' => ['@id' => \App\Support\Seo::id('website')],
    'publisher' => ['@id' => \App\Support\Seo::id('organization')],
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $upcoming->values()->map(fn ($event, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'url' => route('community.show', $event),
            'name' => $event->title,
        ])->all(),
    ],
]) !!}</script>
@endpush

@section('content')
<div>
    {{-- ── Hero ─────────────────────────────────────────────────── --}}
    <section class="relative bg-brand-dark text-white overflow-hidden">
        <div class="pointer-events-none absolute -top-32 -right-32 w-[36rem] h-[36rem] rounded-full bg-brand-gold/10 blur-3xl"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 lg:py-28">
            <p class="text-brand-gold text-xs font-semibold uppercase tracking-[0.2em] mb-4">BG Community</p>
            <h1 class="text-4xl md:text-6xl font-black leading-tight max-w-4xl">
                Meet your next <span class="text-brand-gold">customer, vendor or partner.</span>
            </h1>
            <p class="mt-8 text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl">
                BG Community is the business networking circle of Business Giseness — a place for founders, MSME owners and
                entrepreneurs in Bengaluru and across Karnataka to connect with like-minded business owners, find the right
                customers and suppliers, and grow together.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-3">
                <a href="#meetups" class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold px-7 py-3.5 transition-colors">
                    <i class="far fa-calendar-check" aria-hidden="true"></i> See upcoming meetups
                </a>
                <a href="#how-it-works" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 hover:border-brand-gold text-white font-semibold px-7 py-3.5 transition-colors">
                    How it works
                </a>
            </div>
        </div>
    </section>

    {{-- ── Why join ─────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid gap-6 md:grid-cols-3">
            @foreach([
                ['icon' => 'fa-user-tie',   'title' => 'Find customers',            'text' => 'Meet business owners who need what you offer — and introduce yourself in a room built for real conversations, not cold pitches.'],
                ['icon' => 'fa-truck',      'title' => 'Find vendors & partners',   'text' => 'Discover trusted suppliers, service providers and collaborators recommended by people who have actually worked with them.'],
                ['icon' => 'fa-handshake',  'title' => 'Grow with like-minded owners', 'text' => 'Swap lessons with founders facing the same challenges — hiring, cash flow, marketing, scaling — and leave with useful connections.'],
            ] as $point)
                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-7">
                    <span class="w-11 h-11 rounded-xl bg-brand-dark text-brand-gold flex items-center justify-center">
                        <i class="fas {{ $point['icon'] }}" aria-hidden="true"></i>
                    </span>
                    <h2 class="mt-5 font-bold text-xl text-brand-dark">{{ $point['title'] }}</h2>
                    <p class="mt-2 text-gray-600 leading-relaxed">{{ $point['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ── Upcoming meetups ─────────────────────────────────────── --}}
    <section id="meetups" class="py-20 bg-gray-50 scroll-mt-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">Upcoming meetups</h2>
            <p class="mt-2 text-gray-600">Pick a meetup and save your seat — it takes less than a minute.</p>

            @if($upcoming->isNotEmpty())
                <div class="mt-10 grid gap-6 md:grid-cols-2">
                    @foreach($upcoming as $event)
                        <article class="group flex gap-5 rounded-2xl bg-white border border-gray-100 p-6 shadow-sm hover:shadow-lg transition-shadow">
                            <div class="flex-shrink-0 w-16 text-center rounded-xl bg-brand-dark text-white py-3">
                                <p class="text-xs uppercase tracking-wider text-brand-gold">{{ $event->starts_at->format('M') }}</p>
                                <p class="text-2xl font-black leading-none mt-1">{{ $event->starts_at->format('j') }}</p>
                                <p class="text-[11px] text-white/60 mt-1">{{ $event->starts_at->format('D') }}</p>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-brand-dark leading-snug">
                                    <a href="{{ route('community.show', $event) }}" class="hover:text-brand-gold">{{ $event->title }}</a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>{{ $event->starts_at->format('g:i A') }}
                                    <span class="mx-1">·</span>
                                    <i class="fas {{ $event->is_online ? 'fa-video' : 'fa-map-marker-alt' }} mr-1"></i>{{ $event->where_label }}
                                </p>
                                <p class="mt-3 text-gray-600 text-sm leading-relaxed">{{ $event->summary }}</p>
                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    @if($event->acceptsRegistrations())
                                        <a href="{{ route('community.show', $event) }}#signup" class="inline-flex items-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-semibold text-sm px-5 py-2 transition-colors">
                                            Sign up <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                                        </a>
                                        @if(!is_null($event->spotsLeft()) && $event->spotsLeft() <= 10)
                                            <span class="text-xs font-semibold text-amber-700">Only {{ $event->spotsLeft() }} {{ Str::plural('seat', $event->spotsLeft()) }} left</span>
                                        @endif
                                    @else
                                        <span class="text-sm font-semibold text-gray-500">{{ $event->isFull() ? 'Fully booked' : 'Sign-ups closed' }}</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-10 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                    <i class="far fa-calendar text-4xl text-gray-300"></i>
                    <h3 class="mt-4 text-lg font-bold text-brand-dark">The next meetup is being planned</h3>
                    <p class="mt-2 text-gray-600 max-w-lg mx-auto">
                        Subscribe to our newsletter at the bottom of this page and we'll let you know as soon as sign-ups open.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- ── How it works ─────────────────────────────────────────── --}}
    <section id="how-it-works" class="py-20 bg-white scroll-mt-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">How it works</h2>
            <ol class="mt-10 grid gap-8 md:grid-cols-3">
                @foreach([
                    ['Pick a meetup', 'Choose an upcoming BG Community meetup that suits your schedule.'],
                    ['Save your seat', 'Sign up with your name, company, email and phone number — no fees, no long forms.'],
                    ['Show up and connect', 'Meet business owners, share what you do, and leave with customers, vendors and partners to follow up with.'],
                ] as $i => [$title, $text])
                    <li class="relative pl-14">
                        <span class="absolute left-0 top-0 w-10 h-10 rounded-full border-2 border-brand-gold text-brand-gold font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <h3 class="font-bold text-lg text-brand-dark">{{ $title }}</h3>
                        <p class="mt-1 text-gray-600">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ── Past meetups ─────────────────────────────────────────── --}}
    @if($past->isNotEmpty())
        <section class="py-16 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <h2 class="text-2xl font-bold text-brand-dark">Past meetups</h2>
                <ul class="mt-6 divide-y divide-gray-200 rounded-2xl bg-white border border-gray-100">
                    @foreach($past as $event)
                        <li class="flex flex-wrap items-center justify-between gap-2 px-5 py-4">
                            <a href="{{ route('community.show', $event) }}" class="font-semibold text-brand-dark hover:text-brand-gold">{{ $event->title }}</a>
                            <span class="text-sm text-gray-500">{{ $event->starts_at->format('j M Y') }} · {{ $event->where_label }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
</div>
@endsection
