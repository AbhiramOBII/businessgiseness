@php
    use App\Support\Seo;

    $socials   = Seo::socials();
    $contact   = Seo::contact();
    $stats     = Seo::stats();
    $phoneHref = preg_replace('/[^0-9+]/', '', $contact['phone']);

    $socialIcons = [
        'youtube'   => ['icon' => 'fab fa-youtube',     'label' => 'YouTube'],
        'spotify'   => ['icon' => 'fab fa-spotify',     'label' => 'Spotify'],
        'instagram' => ['icon' => 'fab fa-instagram',   'label' => 'Instagram'],
        'linkedin'  => ['icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'],
    ];

    $columns = [
        'Watch & Listen' => array_filter([
            isset($socials['youtube'])         ? ['label' => 'YouTube — Kannada', 'url' => $socials['youtube'], 'external' => true] : null,
            isset($socials['youtube_english']) ? ['label' => 'YouTube — English', 'url' => $socials['youtube_english'], 'external' => true] : null,
            isset($socials['spotify'])         ? ['label' => 'Spotify', 'url' => $socials['spotify'], 'external' => true] : null,
        ]),
        'Episodes' => [
            ['label' => 'All Episodes',             'url' => route('episodes.index')],
            ['label' => 'Kannada Episodes',         'url' => route('episodes.index', ['language' => 'kn'])],
            ['label' => 'English Episodes',         'url' => route('episodes.index', ['language' => 'en'])],
            ['label' => 'Guests',                   'url' => route('guests.index')],
        ],
        'The Show' => [
            ['label' => 'About the Podcast',        'url' => route('about-business-giseness-podcast')],
            ['label' => 'Kannada Business Podcast', 'url' => route('kannada-business-podcast')],
            ['label' => 'Blog & Insights',          'url' => route('blog.index')],
            ['label' => 'Be a Guest',               'url' => route('home') . '#collaborate'],
        ],
    ];
@endphp

<footer class="bg-brand-dark text-white" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Business Giseness — Kannada Business Podcast</h2>

    {{-- ── Brand row ─────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-14 flex flex-col gap-10 lg:flex-row lg:items-end lg:justify-between border-b border-white/10">
        <div class="max-w-lg">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-4" aria-label="Business Giseness home">
                <img src="{{ asset('images/logo-english-white-gold.png') }}" alt="Business Giseness" class="h-10 w-auto object-contain" loading="lazy">
                <span class="h-7 w-px bg-white/15" aria-hidden="true"></span>
                <img src="{{ asset('images/logo-Kannada-white-gold.png') }}" alt="{{ Seo::NAME_KN }}" class="h-10 w-auto object-contain" loading="lazy">
            </a>
            <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-white/50">About the show</p>
            <p class="mt-3 text-sm leading-relaxed text-white/60">
                A space where small businesses take centre stage. Business Giseness sits down with the real builders of our
                economy — local entrepreneurs, MSME owners and everyday dreamers who turned ideas into livelihoods — to talk
                honestly about the challenges they faced, the risks they took and the determination that keeps them going.
            </p>
            <p lang="kn" class="mt-2 text-sm text-white/40">{{ Seo::TAGLINE_KN }}</p>
            @if($stats)
                <p class="mt-4 text-xs tracking-wide text-white/40">
                    @foreach($stats as $stat)
                        <span class="text-white/70 font-semibold">{{ $stat['value'] }}</span> {{ strtolower($stat['label']) }}@if(!$loop->last)<span class="mx-2" aria-hidden="true">·</span>@endif
                    @endforeach
                </p>
            @endif
            <ul class="mt-6 flex gap-2" aria-label="Business Giseness on social media">
                @foreach($socialIcons as $platform => $meta)
                    @isset($socials[$platform])
                        <li>
                            <a href="{{ $socials[$platform] }}" target="_blank" rel="noopener noreferrer me"
                               aria-label="Business Giseness on {{ $meta['label'] }}"
                               class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white/70 hover:border-brand-gold hover:text-brand-gold transition-colors">
                                <i class="{{ $meta['icon'] }}" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endisset
                @endforeach
            </ul>
        </div>

        <div class="w-full lg:max-w-md">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/50 mb-3">Get new episodes by email</p>
            <livewire:newsletter-subscribe />
        </div>
    </div>

    {{-- ── Link columns ──────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid gap-10 grid-cols-2 lg:grid-cols-4">
        @foreach($columns as $heading => $links)
            <nav aria-label="{{ $heading }}">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-white/50 mb-5">{{ $heading }}</h3>
                <ul class="space-y-3">
                    @foreach($links as $link)
                        <li>
                            <a href="{{ $link['url'] }}" @if(!empty($link['external'])) target="_blank" rel="noopener noreferrer" @endif
                               class="text-sm text-white/70 hover:text-brand-gold transition-colors">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endforeach

        <div>
            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-white/50 mb-5">Get in Touch</h3>
            <address class="not-italic space-y-3 text-sm text-white/70">
                <p>Bengaluru, Karnataka</p>
                <p><a href="tel:{{ $phoneHref }}" class="hover:text-brand-gold transition-colors">{{ $contact['phone'] }}</a></p>
                <p><a href="mailto:{{ $contact['email'] }}" class="hover:text-brand-gold transition-colors break-all">{{ $contact['email'] }}</a></p>
            </address>
        </div>
    </div>

    {{-- ── Bottom bar ────────────────────────────────────────────── --}}
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-white/40">
            <p class="text-center md:text-left">
                &copy; {{ date('Y') }} Business Giseness <span lang="kn">({{ Seo::NAME_KN }})</span>.
                Hosted by <a href="{{ Seo::HOST_URL }}" target="_blank" rel="noopener" class="text-white/60 hover:text-brand-gold transition-colors">{{ Seo::HOST_NAME }}</a>
                · Produced by <a href="{{ Seo::PRODUCER_URL }}" target="_blank" rel="noopener" class="text-white/60 hover:text-brand-gold transition-colors">{{ Seo::PRODUCER_NAME }}</a>
            </p>
            <nav aria-label="Legal" class="flex items-center gap-5 flex-wrap justify-center">
                <a href="{{ route('privacy-policy') }}" class="hover:text-brand-gold transition-colors">Privacy</a>
                <a href="{{ route('terms-of-use') }}" class="hover:text-brand-gold transition-colors">Terms</a>
                <a href="{{ route('disclaimer') }}" class="hover:text-brand-gold transition-colors">Disclaimer</a>
                <a href="{{ route('sitemap') }}" class="hover:text-brand-gold transition-colors">Sitemap</a>
            </nav>
        </div>
    </div>
</footer>
