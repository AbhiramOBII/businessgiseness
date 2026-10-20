@php
    use App\Support\Seo;

    $socials        = Seo::socials();
    $contact        = Seo::contact();
    $stats          = Seo::stats();
    $footerEpisodes = Seo::latestEpisodes(4);
    $phoneHref      = preg_replace('/[^0-9+]/', '', $contact['phone']);

    $socialMeta = [
        'youtube'   => ['icon' => 'fab fa-youtube',   'label' => 'YouTube'],
        'spotify'   => ['icon' => 'fab fa-spotify',   'label' => 'Spotify'],
        'instagram' => ['icon' => 'fab fa-instagram', 'label' => 'Instagram'],
        'linkedin'  => ['icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'],
    ];

    $exploreLinks = [
        ['label' => 'Home',                     'url' => route('home')],
        ['label' => 'About the Podcast',        'url' => route('about-business-giseness-podcast')],
        ['label' => 'Kannada Business Podcast', 'url' => route('kannada-business-podcast')],
        ['label' => 'All Episodes',             'url' => route('episodes.index')],
        ['label' => 'Kannada Episodes',         'url' => route('episodes.index', ['language' => 'kn'])],
        ['label' => 'Guests',                   'url' => route('guests.index')],
        ['label' => 'Blog & Insights',          'url' => route('blog.index')],
    ];
@endphp

<footer class="bg-brand-dark text-white relative overflow-hidden" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Business Giseness — Kannada Business Podcast</h2>

    {{-- Gold hairline + soft glow --}}
    <div class="h-px bg-gradient-to-r from-transparent via-brand-gold/70 to-transparent"></div>
    <div class="pointer-events-none absolute -top-40 left-1/2 -translate-x-1/2 w-[48rem] h-80 rounded-full bg-brand-gold/10 blur-3xl"></div>

    {{-- ── Positioning band + newsletter ─────────────────────────── --}}
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-14 pb-12">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center rounded-2xl border border-white/10 bg-white/[0.03] p-6 sm:p-8 md:p-10">
            <div class="lg:col-span-7">
                <p class="text-brand-gold text-xs font-semibold uppercase tracking-[0.2em] mb-3">
                    {{ Seo::CATEGORY }} <span class="text-white/30 mx-1">·</span> <span lang="kn" class="normal-case tracking-normal">{{ Seo::CATEGORY_KN }}</span>
                </p>
                <p class="text-2xl md:text-3xl font-bold leading-tight">
                    Karnataka's founders, <span class="text-brand-gold">in their own words.</span>
                </p>
                <p lang="kn" class="mt-2 text-lg text-gray-400">{{ Seo::TAGLINE_KN }}</p>
            </div>
            <div class="lg:col-span-5">
                <p class="text-sm text-gray-300 mb-3">New episodes and founder lessons, straight to your inbox.</p>
                <livewire:newsletter-subscribe />
            </div>
        </div>
    </div>

    {{-- ── Main columns ──────────────────────────────────────────── --}}
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">

            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-4" aria-label="Business Giseness home">
                    <img src="{{ asset('images/logo-english-white-gold.png') }}" alt="Business Giseness" class="h-11 w-auto object-contain" loading="lazy">
                    <span class="h-8 w-px bg-white/15" aria-hidden="true"></span>
                    <img src="{{ asset('images/logo-Kannada-white-gold.png') }}" alt="{{ Seo::NAME_KN }}" class="h-11 w-auto object-contain" loading="lazy">
                </a>

                <p class="mt-5 text-sm leading-relaxed text-gray-400">{{ Seo::summary() }}</p>

                @if($stats)
                    <dl class="mt-6 grid grid-cols-3 gap-2 max-w-sm">
                        @foreach($stats as $stat)
                            <div class="flex flex-col-reverse rounded-xl border border-white/10 bg-white/[0.03] px-2 py-3 text-center">
                                <dt class="mt-0.5 text-[11px] uppercase tracking-wider text-gray-500">{{ $stat['label'] }}</dt>
                                <dd class="text-lg font-bold text-brand-gold">{{ $stat['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif

                <ul class="mt-6 flex gap-2" aria-label="Business Giseness on social media">
                    @foreach($socials as $platform => $url)
                        <li>
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer me"
                               aria-label="Business Giseness on {{ $socialMeta[$platform]['label'] }}"
                               class="w-10 h-10 rounded-full border border-white/10 bg-white/5 flex items-center justify-center text-gray-300 hover:bg-brand-gold hover:border-brand-gold hover:text-brand-dark transition-colors">
                                <i class="{{ $socialMeta[$platform]['icon'] }}" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Explore --}}
            <nav class="lg:col-span-2" aria-label="Footer">
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Explore</h3>
                <ul class="space-y-3">
                    @foreach($exploreLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}" class="text-sm text-gray-400 hover:text-brand-gold transition-colors">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- Latest episodes --}}
            <div class="lg:col-span-3">
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Latest Episodes</h3>
                @if($footerEpisodes->isNotEmpty())
                    <ul class="space-y-4">
                        @foreach($footerEpisodes as $episode)
                            <li>
                                <a href="{{ route('episodes.show', $episode) }}" class="group block">
                                    <span class="text-sm leading-snug text-gray-300 group-hover:text-brand-gold transition-colors line-clamp-2">{{ $episode->title }}</span>
                                    <span class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                        @if($episode->published_at)
                                            <time datetime="{{ $episode->published_at->toDateString() }}">{{ $episode->formatted_published_at }}</time>
                                        @endif
                                        @if($episode->language_label)
                                            <span class="rounded-full border border-white/10 px-2 py-px">{{ $episode->language_label }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('episodes.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-gold hover:text-yellow-400 transition-colors">
                        All episodes <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                @else
                    <p class="text-sm text-gray-500">New episodes are on the way.</p>
                @endif
            </div>

            {{-- Listen + contact --}}
            <div class="lg:col-span-3">
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Watch &amp; Listen</h3>
                <div class="flex flex-col gap-2">
                    @isset($socials['youtube'])
                        <a href="{{ $socials['youtube'] }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 hover:border-brand-gold/60 transition-colors">
                            <i class="fab fa-youtube text-xl text-red-500" aria-hidden="true"></i>
                            <span class="text-sm leading-tight"><span class="block text-[11px] text-gray-500">Watch on</span>YouTube</span>
                        </a>
                    @endisset
                    @isset($socials['spotify'])
                        <a href="{{ $socials['spotify'] }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 hover:border-brand-gold/60 transition-colors">
                            <i class="fab fa-spotify text-xl text-green-500" aria-hidden="true"></i>
                            <span class="text-sm leading-tight"><span class="block text-[11px] text-gray-500">Listen on</span>Spotify</span>
                        </a>
                    @endisset
                </div>

                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mt-8 mb-4">Get in Touch</h3>
                <address class="not-italic space-y-3 text-sm text-gray-400">
                    <a href="mailto:{{ $contact['email'] }}" class="flex items-center gap-3 hover:text-brand-gold transition-colors break-all">
                        <i class="fas fa-envelope text-brand-gold w-4" aria-hidden="true"></i>{{ $contact['email'] }}
                    </a>
                    <a href="tel:{{ $phoneHref }}" class="flex items-center gap-3 hover:text-brand-gold transition-colors">
                        <i class="fas fa-phone text-brand-gold w-4" aria-hidden="true"></i>{{ $contact['phone'] }}
                    </a>
                    <p class="flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-brand-gold w-4" aria-hidden="true"></i>Bengaluru, Karnataka, India
                    </p>
                </address>

                <a href="{{ route('home') }}#collaborate"
                   class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold text-sm px-5 py-3 transition-colors">
                    <i class="fas fa-microphone text-xs" aria-hidden="true"></i> Be a Guest
                </a>
            </div>
        </div>
    </div>

    {{-- ── Bottom bar ────────────────────────────────────────────── --}}
    <div class="relative border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div class="text-center md:text-left space-y-1">
                <p>&copy; {{ date('Y') }} <span class="text-gray-300 font-semibold">Business Giseness</span> <span lang="kn">({{ Seo::NAME_KN }})</span>. All rights reserved.</p>
                <p>
                    Hosted by <a href="{{ Seo::HOST_URL }}" target="_blank" rel="noopener" class="text-brand-gold hover:text-yellow-400 transition-colors">{{ Seo::HOST_NAME }}</a>
                    · Produced by <a href="{{ Seo::PRODUCER_URL }}" target="_blank" rel="noopener" class="text-brand-gold hover:text-yellow-400 transition-colors">{{ Seo::PRODUCER_NAME }}</a>, Bengaluru
                </p>
            </div>
            <nav aria-label="Legal" class="flex items-center gap-5 flex-wrap justify-center">
                <a href="{{ route('privacy-policy') }}" class="hover:text-brand-gold transition-colors">Privacy Policy</a>
                <a href="{{ route('terms-of-use') }}" class="hover:text-brand-gold transition-colors">Terms of Use</a>
                <a href="{{ route('disclaimer') }}" class="hover:text-brand-gold transition-colors">Disclaimer</a>
                <a href="{{ route('sitemap') }}" class="hover:text-brand-gold transition-colors">Sitemap</a>
            </nav>
        </div>
    </div>
</footer>
