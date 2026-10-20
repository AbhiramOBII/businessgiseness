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
            ['label' => 'FAQ',                      'url' => route('home') . '#faq'],
        ],
    ];
@endphp

<footer class="bg-brand-dark text-white" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Business Giseness — Kannada Business Podcast</h2>

    {{-- ── Brand row ─────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-14 grid gap-12 lg:grid-cols-2 lg:gap-16 border-b border-white/10">
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

        <div class="flex flex-col justify-between gap-12 lg:max-w-md lg:justify-self-end w-full">
            {{-- Guest invitation --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold">Be a guest</p>
                <p class="mt-3 text-2xl md:text-3xl font-bold leading-tight">
                    Building something in Karnataka? <span class="text-white/50">Your story deserves to be heard.</span>
                </p>
                <p class="mt-3 text-sm leading-relaxed text-white/60">
                    We're always looking for founders, MSME owners and makers who are still in the middle of the journey — in Kannada or English.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-3">
                    <a href="mailto:{{ $contact['email'] }}?subject={{ rawurlencode('Guest enquiry — Business Giseness') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-semibold text-sm px-5 py-2.5 transition-colors">
                        <i class="fas fa-microphone text-xs" aria-hidden="true"></i> Pitch your story
                    </a>
                    <a href="tel:{{ $phoneHref }}" class="text-sm text-white/70 hover:text-brand-gold transition-colors">
                        or call {{ $contact['phone'] }}
                    </a>
                </div>
            </div>

            {{-- Newsletter --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/50 mb-3">Get new episodes by email</p>
                <livewire:newsletter-subscribe />
            </div>
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

    {{-- ── The Obii family ───────────────────────────────────────── --}}
    @php
        $family = [
            ['name' => 'Obii Kriationz',   'url' => 'https://www.obiikriationz.com/', 'logo' => 'images/brands/obii-kriationz.svg',   'class' => 'h-7'],
            ['name' => 'ObiiNxt',          'url' => 'https://www.obiinxt.com/',       'logo' => 'images/brands/obiinxt.png',          'class' => 'h-7'],
            ['name' => 'Obii Studios',     'url' => null,                             'logo' => 'images/brands/obii-studios.png',     'class' => 'h-10'],
            ['name' => 'Digital Dopamine', 'url' => 'https://digitaldopamine.in/',    'logo' => 'images/brands/digital-dopamine.png', 'class' => 'h-14 brightness-0 invert'],
        ];
    @endphp
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-10">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/50 whitespace-nowrap">The Obii family</p>
            <ul class="flex flex-wrap items-center gap-x-10 gap-y-6">
                @foreach($family as $brand)
                    <li>
                        @if($brand['url'])
                            <a href="{{ $brand['url'] }}" target="_blank" rel="noopener" title="{{ $brand['name'] }}"
                               class="block opacity-70 hover:opacity-100 transition-opacity">
                                <img src="{{ asset($brand['logo']) }}" alt="{{ $brand['name'] }}" class="{{ $brand['class'] }} w-auto object-contain" loading="lazy">
                            </a>
                        @else
                            <span class="block opacity-70" title="{{ $brand['name'] }}">
                                <img src="{{ asset($brand['logo']) }}" alt="{{ $brand['name'] }}" class="{{ $brand['class'] }} w-auto object-contain" loading="lazy">
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
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
