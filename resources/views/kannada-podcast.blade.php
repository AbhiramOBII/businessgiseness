@extends('layouts.app')

@php
    use App\Support\Seo;

    $pageTitle       = 'Kannada Business Podcast — Business Giseness | ಕನ್ನಡ ಬಿಸಿನೆಸ್ ಪಾಡ್‌ಕ್ಯಾಸ್ಟ್';
    $pageDescription = 'Business Giseness is a Kannada business podcast from Bengaluru: long-form conversations with Karnataka\'s founders about startups, family business, growth and failure. Watch in Kannada & English.';
    $socials         = Seo::socials();
    $stats           = Seo::stats();
    $listed          = $kannadaEpisodes->concat($recentEpisodes);
@endphp

@section('title', $pageTitle)
@section('meta-description', $pageDescription)
@section('meta_keywords', 'Kannada business podcast, Kannada podcast, business podcast in Kannada, Kannada entrepreneur podcast, Karnataka startup stories, Bengaluru founders, ಕನ್ನಡ ಪಾಡ್‌ಕ್ಯಾಸ್ಟ್, ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್')
@section('og_title', 'Business Giseness — the Kannada Business Podcast')
@section('og_description', $pageDescription)
@section('canonical_url', route('kannada-business-podcast'))

@push('head')
<script type="application/ld+json">{!! Seo::json([
    '@context'   => 'https://schema.org',
    '@type'      => 'CollectionPage',
    'url'        => route('kannada-business-podcast'),
    'name'       => $pageTitle,
    'description'=> $pageDescription,
    'isPartOf'   => ['@id' => Seo::id('website')],
    'about'      => ['@id' => Seo::id('podcast')],
    'inLanguage' => ['en', 'kn'],
    'mainEntity' => [
        '@type' => 'ItemList',
        'name' => 'Business Giseness episodes',
        'itemListElement' => $listed->values()->map(fn ($episode, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'url' => route('episodes.show', $episode),
            'name' => $episode->title,
        ])->all(),
    ],
]) !!}</script>
<script type="application/ld+json">{!! Seo::json([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Kannada Business Podcast', 'item' => route('kannada-business-podcast')],
    ],
]) !!}</script>
@endpush

@section('content')
<div>
    {{-- ── Hero ─────────────────────────────────────────────────── --}}
    <section class="relative bg-brand-dark text-white overflow-hidden">
        <div class="pointer-events-none absolute -top-32 -right-32 w-[36rem] h-[36rem] rounded-full bg-brand-gold/10 blur-3xl"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 lg:py-28">
            <nav aria-label="Breadcrumb" class="text-xs text-gray-500 mb-8">
                <a href="{{ route('home') }}" class="hover:text-brand-gold">Home</a>
                <span class="mx-2">/</span>
                <span class="text-gray-300">Kannada Business Podcast</span>
            </nav>

            <p class="text-brand-gold text-xs font-semibold uppercase tracking-[0.2em] mb-4">
                {{ Seo::CATEGORY }} <span class="text-white/30 mx-1">·</span> <span lang="kn" class="normal-case tracking-normal">{{ Seo::CATEGORY_KN }}</span>
            </p>
            <h1 class="text-4xl md:text-6xl font-black leading-tight max-w-4xl">
                The Kannada business podcast for <span class="text-brand-gold">Karnataka's founders</span>
            </h1>
            <p lang="kn" class="mt-4 text-2xl text-gray-300">{{ Seo::TAGLINE_KN }}</p>

            <p class="mt-8 text-lg md:text-xl text-gray-300 leading-relaxed max-w-3xl">
                {{ Seo::summary() }}
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-3">
                @isset($socials['youtube'])
                    <a href="{{ $socials['youtube'] }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold px-7 py-3.5 transition-colors">
                        <i class="fab fa-youtube" aria-hidden="true"></i> Watch on YouTube
                    </a>
                @endisset
                @isset($socials['spotify'])
                    <a href="{{ $socials['spotify'] }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 hover:border-brand-gold text-white font-semibold px-7 py-3.5 transition-colors">
                        <i class="fab fa-spotify" aria-hidden="true"></i> Listen on Spotify
                    </a>
                @endisset
                <a href="{{ route('episodes.index', ['language' => 'kn']) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 hover:border-brand-gold text-white font-semibold px-7 py-3.5 transition-colors">
                    Browse Kannada episodes <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
            </div>

            @if($stats)
                <dl class="mt-14 grid grid-cols-3 gap-4 max-w-xl">
                    @foreach($stats as $stat)
                        <div class="flex flex-col-reverse border-l border-brand-gold/40 pl-4">
                            <dt class="text-xs uppercase tracking-wider text-gray-500 mt-1">{{ $stat['label'] }}</dt>
                            <dd class="text-3xl font-bold text-white">{{ $stat['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
    </section>

    {{-- ── Why listen ───────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                    <span class="w-6 h-px bg-brand-gold inline-block"></span> Why Business Giseness
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-brand-dark mt-3">Business conversations in the language Karnataka thinks in</h2>
                <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                    Most business podcasts are made for a global, English-first audience. Business Giseness sits down with founders from Bengaluru,
                    Malnad, the coast and across Karnataka — and lets them tell their story in Kannada, the way they would tell it at home.
                </p>
            </div>

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => 'fa-language',      'title' => 'Made in Kannada',       'text' => 'Conversations in Kannada, with selected episodes in English, so the stories reach every listener in Karnataka and beyond.'],
                    ['icon' => 'fa-seedling',      'title' => 'Founders still building', 'text' => 'Not just celebrity success stories — the people in the middle of the journey, with the doubts and decisions still fresh.'],
                    ['icon' => 'fa-industry',      'title' => 'Every kind of business', 'text' => 'Startups, family businesses, food brands, real estate, education, cinema, marketing, manufacturing and more.'],
                    ['icon' => 'fa-lightbulb',     'title' => 'Lessons you can use',    'text' => 'Every episode digs into what actually worked, what failed, and what the guest would do differently.'],
                ] as $point)
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6">
                        <span class="w-11 h-11 rounded-xl bg-brand-dark text-brand-gold flex items-center justify-center">
                            <i class="fas {{ $point['icon'] }}" aria-hidden="true"></i>
                        </span>
                        <h3 class="mt-5 font-bold text-lg text-brand-dark">{{ $point['title'] }}</h3>
                        <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $point['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Episodes ─────────────────────────────────────────────── --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            @if($kannadaEpisodes->isNotEmpty())
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">Kannada episodes</h2>
                        <p lang="kn" class="mt-2 text-gray-500">ಕನ್ನಡದಲ್ಲಿ ಸಂಚಿಕೆಗಳು</p>
                    </div>
                    <a href="{{ route('episodes.index', ['language' => 'kn']) }}" class="text-sm font-semibold text-brand-dark hover:text-brand-gold">
                        All Kannada episodes <i class="fas fa-arrow-right text-xs ml-1" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kannadaEpisodes as $episode)
                        @include('partials.episode-card', ['episode' => $episode])
                    @endforeach
                </div>
            @endif

            @if($recentEpisodes->isNotEmpty())
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 {{ $kannadaEpisodes->isNotEmpty() ? 'mt-20' : '' }}">
                    <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">{{ $kannadaEpisodes->isNotEmpty() ? 'More recent episodes' : 'Latest episodes' }}</h2>
                    <a href="{{ route('episodes.index') }}" class="text-sm font-semibold text-brand-dark hover:text-brand-gold">
                        All {{ $totalEpisodes }} episodes <i class="fas fa-arrow-right text-xs ml-1" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentEpisodes as $episode)
                        @include('partials.episode-card', ['episode' => $episode])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ── Host ─────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 grid md:grid-cols-5 gap-10 items-center">
            <div class="md:col-span-2">
                <img src="{{ Seo::hostImageUrl() }}" alt="{{ Seo::HOST_NAME }}, host of Business Giseness" loading="lazy"
                     class="w-full max-w-xs mx-auto rounded-2xl object-cover shadow-lg">
            </div>
            <div class="md:col-span-3">
                <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                    <span class="w-6 h-px bg-brand-gold inline-block"></span> Your host
                </span>
                <h2 class="text-3xl font-bold text-brand-dark mt-3">{{ Seo::HOST_NAME }}</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    {{ Seo::HOST_NAME }} is a Bengaluru-based entrepreneur and the founder of Obii Kriationz. Business Giseness was started to
                    celebrate founders long before the spotlight arrives — and to make honest business conversations accessible in Kannada.
                </p>
                <a href="{{ route('about-business-giseness-podcast') }}" class="mt-6 inline-flex items-center gap-2 font-semibold text-brand-dark hover:text-brand-gold">
                    The story behind the show <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('partials.faq', [
        'heading' => 'About the <span class="text-brand-gold">Kannada business podcast</span>',
        'theme' => 'dark',
    ])

    {{-- ── Guest CTA ────────────────────────────────────────────── --}}
    <section class="py-16 bg-brand-gold">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-dark">Building something in Karnataka?</h2>
                <p class="mt-1 text-brand-dark/80">Your story deserves to be heard — in Kannada or English.</p>
            </div>
            <a href="mailto:{{ Seo::contact()['email'] }}?subject={{ rawurlencode('Guest enquiry — Business Giseness') }}"
               class="inline-flex items-center gap-2 rounded-full bg-brand-dark hover:bg-gray-800 text-white font-bold px-7 py-3.5 transition-colors whitespace-nowrap">
                <i class="fas fa-microphone text-sm" aria-hidden="true"></i> Be a guest
            </a>
        </div>
    </section>
</div>
@endsection
