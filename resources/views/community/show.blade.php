@extends('layouts.app')

@php
    use App\Support\Seo;

    $pageTitle = $event->title . ' — BG Community | Business Giseness';
    $isOpen = $event->acceptsRegistrations();
    $spotsLeft = $event->spotsLeft();

    $eventSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $event->title,
        'description' => $event->summary,
        'url' => route('community.show', $event),
        'image' => $event->cover_url ?: Seo::logoUrl(),
        'startDate' => $event->starts_at->toIso8601String(),
        'endDate' => $event->ends_at?->toIso8601String(),
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => $event->is_online ? 'https://schema.org/OnlineEventAttendanceMode' : 'https://schema.org/OfflineEventAttendanceMode',
        'location' => $event->is_online
            ? ['@type' => 'VirtualLocation', 'url' => route('community.show', $event)]
            : array_filter([
                '@type' => 'Place',
                'name' => $event->venue_name,
                'address' => array_filter([
                    '@type' => 'PostalAddress',
                    'streetAddress' => $event->venue_address,
                    'addressLocality' => 'Bengaluru',
                    'addressRegion' => 'Karnataka',
                    'addressCountry' => 'IN',
                ]),
            ]),
        'organizer' => ['@id' => Seo::id('organization')],
        'maximumAttendeeCapacity' => $event->capacity,
    ]);
@endphp

@section('title', $pageTitle)
@section('meta-description', $event->summary)
@section('og_type', 'website')
@section('og_title', $event->title . ' — BG Community')
@section('og_description', $event->summary)
@if($event->cover_url)
@section('og_image', $event->cover_url)
@section('twitter_image', $event->cover_url)
@endif
@section('canonical_url', route('community.show', $event))

@push('head')
@unless($event->is_published)
<meta name="robots" content="noindex">
@endunless
<script type="application/ld+json">{!! Seo::json($eventSchema) !!}</script>
<script type="application/ld+json">{!! Seo::json([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'BG Community', 'item' => route('community.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $event->title, 'item' => route('community.show', $event)],
    ],
]) !!}</script>
@endpush

@section('content')
<div class="bg-gray-50">
    {{-- ── Header ───────────────────────────────────────────────── --}}
    <section class="bg-brand-dark text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 lg:py-20">
            <nav aria-label="Breadcrumb" class="text-xs text-white/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-brand-gold">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('community.index') }}" class="hover:text-brand-gold">BG Community</a>
            </nav>
            @unless($event->is_published)
                <p class="mb-4 inline-block rounded-full bg-amber-400/20 text-amber-300 text-xs font-semibold px-3 py-1">Draft — only admins can see this page</p>
            @endunless
            <p class="text-brand-gold text-xs font-semibold uppercase tracking-[0.2em] mb-3">BG Community meetup</p>
            <h1 class="text-3xl md:text-5xl font-black leading-tight max-w-4xl">{{ $event->title }}</h1>
            <div class="mt-6 flex flex-col sm:flex-row sm:flex-wrap gap-x-8 gap-y-3 text-white/80">
                <p><i class="far fa-calendar text-brand-gold mr-2"></i>{{ $event->when_label }}</p>
                <p>
                    <i class="fas {{ $event->is_online ? 'fa-video' : 'fa-map-marker-alt' }} text-brand-gold mr-2"></i>{{ $event->where_label }}
                    @if($event->map_link && !$event->is_online)
                        <a href="{{ $event->map_link }}" target="_blank" rel="noopener" class="ml-2 text-sm text-brand-gold hover:underline">Map</a>
                    @endif
                </p>
                @if($event->capacity && !$event->isPast())
                    <p><i class="fas fa-chair text-brand-gold mr-2"></i>{{ $spotsLeft }} of {{ $event->capacity }} seats left</p>
                @endif
            </div>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 lg:py-16 grid gap-10 lg:grid-cols-5">
        {{-- ── About the meetup ─────────────────────────────────── --}}
        <article class="lg:col-span-3">
            @if($event->cover_url)
                <img src="{{ $event->cover_url }}" alt="{{ $event->title }}" class="w-full rounded-2xl mb-8 object-cover">
            @endif
            <p class="text-xl text-gray-700 leading-relaxed">{{ $event->summary }}</p>
            @if($event->description)
                <div class="mt-6 text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->description }}</div>
            @endif

            <div class="mt-10 rounded-2xl bg-white border border-gray-100 p-6">
                <h2 class="font-bold text-brand-dark">About BG Community</h2>
                <p class="mt-2 text-gray-600 leading-relaxed">
                    BG Community is the business networking circle of Business Giseness, helping founders and MSME owners in Karnataka
                    connect with like-minded business owners — to find customers, vendors and partners.
                    <a href="{{ route('community.index') }}" class="text-brand-dark font-semibold underline decoration-brand-gold/50 underline-offset-4 hover:text-brand-gold">See all meetups</a>
                </p>
            </div>
        </article>

        {{-- ── Sign-up ──────────────────────────────────────────── --}}
        <aside id="signup" class="lg:col-span-2 scroll-mt-28">
            <div class="lg:sticky lg:top-28 rounded-2xl bg-white border border-gray-100 shadow-sm p-6">
                @if(session('registered'))
                    <div class="text-center py-6" role="status">
                        <span class="mx-auto w-14 h-14 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-2xl">
                            <i class="fas fa-check" aria-hidden="true"></i>
                        </span>
                        <h2 class="mt-4 text-xl font-bold text-brand-dark">You're on the list!</h2>
                        <p class="mt-2 text-gray-600">Thanks for signing up. We'll be in touch with the details before the meetup.</p>
                        <a href="{{ route('community.index') }}" class="mt-6 inline-block text-sm font-semibold text-brand-dark hover:text-brand-gold">Back to BG Community</a>
                    </div>
                @elseif($isOpen)
                    <h2 class="text-xl font-bold text-brand-dark">Save your seat</h2>
                    <p class="mt-1 text-sm text-gray-500">Free to attend. Takes less than a minute.</p>

                    @if(session('closed'))
                        <p class="mt-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-3 py-2">Sorry — sign-ups for this meetup have just closed.</p>
                    @endif

                    <form action="{{ route('community.register', $event) }}" method="POST" class="mt-6 space-y-4" novalidate>
                        @csrf
                        {{-- Honeypot: hidden from people, bots tend to fill it --}}
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        @foreach([
                            ['name' => 'name',    'label' => 'Full name',    'type' => 'text',  'autocomplete' => 'name',         'placeholder' => 'Your name'],
                            ['name' => 'company', 'label' => 'Company name', 'type' => 'text',  'autocomplete' => 'organization', 'placeholder' => 'Your business'],
                            ['name' => 'email',   'label' => 'Email',        'type' => 'email', 'autocomplete' => 'email',        'placeholder' => 'you@company.com'],
                            ['name' => 'phone',   'label' => 'Phone number', 'type' => 'tel',   'autocomplete' => 'tel',          'placeholder' => '+91 98765 43210'],
                        ] as $field)
                            <div>
                                <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $field['label'] }}</label>
                                <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="{{ $field['type'] }}" required
                                       autocomplete="{{ $field['autocomplete'] }}" placeholder="{{ $field['placeholder'] }}"
                                       value="{{ old($field['name']) }}"
                                       @error($field['name']) aria-invalid="true" aria-describedby="{{ $field['name'] }}-error" @enderror
                                       class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-brand-gold focus:border-brand-gold {{ $errors->has($field['name']) ? 'border-red-400' : 'border-gray-300' }}">
                                @error($field['name'])<p id="{{ $field['name'] }}-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        @endforeach

                        <button type="submit" class="w-full rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold py-3 transition-colors">
                            Sign me up
                        </button>
                        <p class="text-xs text-gray-500 text-center">
                            We'll only use your details for BG Community. See our <a href="{{ route('privacy-policy') }}" class="underline">privacy policy</a>.
                        </p>
                    </form>
                @else
                    <div class="text-center py-6">
                        <i class="far fa-calendar-times text-4xl text-gray-300"></i>
                        <h2 class="mt-4 text-lg font-bold text-brand-dark">
                            {{ $event->isPast() ? 'This meetup has ended' : ($event->isFull() ? 'This meetup is fully booked' : 'Sign-ups are closed') }}
                        </h2>
                        <p class="mt-2 text-gray-600 text-sm">Catch the next one — see upcoming BG Community meetups.</p>
                        <a href="{{ route('community.index') }}#meetups" class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand-gold text-brand-dark font-semibold text-sm px-5 py-2.5">
                            Upcoming meetups <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
