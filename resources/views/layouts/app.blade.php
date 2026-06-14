<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/bg-icon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/bg-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <title>@yield('title', 'Business Giseness - Founder-First Storytelling Podcast')</title>
    
    <!-- Meta Description -->
    <meta name="description" content="@yield('meta-description', 'Dive deep into real, raw, unfiltered stories of entrepreneurs who are still building. Business Giseness celebrates founder-first narratives with authentic business insights.')">
    <meta name="keywords" content="@yield('meta_keywords', 'business podcast, entrepreneur stories, startup podcast, founder interviews, business giseness, entrepreneurship, business strategy, startup stories')">
    <meta name="author" content="Abhiram Chandramohan">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'Business Giseness - Founder-First Storytelling Podcast')">
    <meta property="og:description" content="@yield('og_description', 'Dive deep into real, raw, unfiltered stories of entrepreneurs who are still building. Authentic founder narratives and business insights.')">
    <meta property="og:image" content="@yield('og_image', asset('images/bg-icon.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Business Giseness">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', 'Business Giseness - Founder-First Storytelling Podcast')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Dive deep into real, raw, unfiltered stories of entrepreneurs who are still building. Authentic founder narratives and business insights.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/bg-icon.png'))">
    <meta name="twitter:creator" content="@abhiramchandram">
    <meta name="twitter:site" content="@businessgiseness">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    
    <!-- Sitemap discovery -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    
    <!-- Theme colour (mobile browser chrome & PWA) -->
    <meta name="theme-color" content="#ba933e">
    
    <!-- Additional Head Content -->
    @stack('head')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Satoshi Font -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#151828',
                        'brand-gold': '#ba933e',
                        'brand-white': '#FFFFFF'
                    },
                    fontFamily: {
                        'sans': ['Satoshi', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --brand-dark: #151828;
            --brand-gold: #ba933e;
            --brand-white: #FFFFFF;
        }
        
        .brand-dark { color: var(--brand-dark); }
        .bg-brand-dark { background-color: var(--brand-dark); }
        .brand-gold { color: var(--brand-gold); }
        .bg-brand-gold { background-color: var(--brand-gold); }
        .brand-white { color: var(--brand-white); }
        .bg-brand-white { background-color: var(--brand-white); }
        .text-brand-dark { color: var(--brand-dark); }
        .text-brand-gold { color: var(--brand-gold); }
        .text-brand-white { color: var(--brand-white); }
        .border-brand-gold { border-color: var(--brand-gold); }
        
        body {
            font-family: 'Satoshi', 'Inter', system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-brand-white">
    <!-- Header -->
    @include('layouts.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer itemscope itemtype="https://schema.org/WPFooter"
            class="bg-brand-dark text-white">

        @php
            $footerDesc    = \App\Models\SiteSetting::get('site_description', 'Business Giseness is a bilingual podcast show featuring real founder stories in English and Kannada.');
            $contactEmail  = \App\Models\SiteSetting::get('contact_email', 'info@obiikriationz.com');
            $contactPhone  = \App\Models\SiteSetting::get('contact_phone', '+91 9964 102 103');
            $socialYoutube   = \App\Models\SiteSetting::get('social_youtube',   'https://www.youtube.com/@BusinessGiseness');
            $socialSpotify   = \App\Models\SiteSetting::get('social_spotify',   'https://creators.spotify.com/pod/profile/business-giseness/');
            $socialInstagram = \App\Models\SiteSetting::get('social_instagram', 'https://www.instagram.com/businessgisenessenglish/');
            $socialLinkedin  = \App\Models\SiteSetting::get('social_linkedin',  'https://www.linkedin.com/company/business-giseness/');
        @endphp
        {{-- Top: newsletter band --}}
        <div class="border-b border-white/10 py-10 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-brand-gold font-semibold text-xs uppercase tracking-widest mb-1">Stay in the Loop</p>
                    <h2 class="text-white font-bold text-xl">Get founder stories delivered to your inbox.</h2>
                </div>
                <div class="w-full md:w-auto md:min-w-[380px]">
                    <livewire:newsletter-subscribe />
                </div>
            </div>
        </div>

        {{-- Main columns --}}
        <div class="max-w-7xl mx-auto px-6 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">

                {{-- Col 1: Brand --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ route('home') }}" aria-label="Business Giseness Home">
                        <img src="{{ asset('images/logo-english-white-gold.png') }}"
                             alt="Business Giseness — Bilingual Founder Podcast"
                             class="h-14 w-auto object-contain mb-5">
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6" itemprop="description">
                        {{ $footerDesc }}
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener noreferrer"
                           aria-label="Business Giseness on Instagram"
                           class="w-9 h-9 bg-white/5 hover:bg-brand-gold rounded-lg flex items-center justify-center text-gray-400 hover:text-brand-dark transition-all">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="{{ $socialYoutube }}" target="_blank" rel="noopener noreferrer"
                           aria-label="Business Giseness on YouTube"
                           class="w-9 h-9 bg-white/5 hover:bg-red-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="{{ $socialSpotify }}" target="_blank" rel="noopener noreferrer"
                           aria-label="Business Giseness on Spotify"
                           class="w-9 h-9 bg-white/5 hover:bg-green-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                            <i class="fab fa-spotify"></i>
                        </a>
                        <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener noreferrer"
                           aria-label="Business Giseness on LinkedIn"
                           class="w-9 h-9 bg-white/5 hover:bg-blue-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                {{-- Col 2: Explore --}}
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Explore</h3>
                    <nav aria-label="Footer navigation">
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('home') }}"
                                   class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-2">
                                    <span class="w-1 h-1 bg-brand-gold rounded-full flex-shrink-0"></span>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about-business-giseness-podcast') }}"
                                   class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-2">
                                    <span class="w-1 h-1 bg-brand-gold rounded-full flex-shrink-0"></span>
                                    About Business Giseness
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('episodes.index') }}"
                                   class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-2">
                                    <span class="w-1 h-1 bg-brand-gold rounded-full flex-shrink-0"></span>
                                    All Episodes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('guests.index') }}"
                                   class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-2">
                                    <span class="w-1 h-1 bg-brand-gold rounded-full flex-shrink-0"></span>
                                    Featured Guests
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('blog.index') }}"
                                   class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-2">
                                    <span class="w-1 h-1 bg-brand-gold rounded-full flex-shrink-0"></span>
                                    Blog &amp; Insights
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                {{-- Col 3: Listen On --}}
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Listen &amp; Watch</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ $socialYoutube }}" target="_blank" rel="noopener noreferrer"
                               class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-3">
                                <i class="fab fa-youtube text-brand-gold w-4"></i>
                                YouTube
                            </a>
                        </li>
                        <li>
                            <a href="{{ $socialSpotify }}" target="_blank" rel="noopener noreferrer"
                               class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-3">
                                <i class="fab fa-spotify text-brand-gold w-4"></i>
                                Spotify Podcasts
                            </a>
                        </li>
                        <li>
                            <a href="{{ $socialInstagram }}" target="_blank" rel="noopener noreferrer"
                               class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-3">
                                <i class="fab fa-instagram text-brand-gold w-4"></i>
                                Instagram Reels
                            </a>
                        </li>
                        <li>
                            <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener noreferrer"
                               class="text-gray-400 hover:text-brand-gold text-sm transition-colors flex items-center gap-3">
                                <i class="fab fa-linkedin text-brand-gold w-4"></i>
                                LinkedIn
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Col 4: Contact --}}
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Get in Touch</h3>
                    <ul class="space-y-4 text-sm" itemscope itemtype="https://schema.org/Organization">
                        <meta itemprop="name" content="Business Giseness">
                        <li itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"
                            class="flex items-start gap-3 text-gray-400">
                            <i class="fas fa-map-marker-alt text-brand-gold mt-0.5 w-4 flex-shrink-0"></i>
                            <span itemprop="addressLocality">Bengaluru</span>,&nbsp;<span itemprop="addressRegion">Karnataka</span>,&nbsp;<span itemprop="addressCountry">India</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="fas fa-phone text-brand-gold w-4 flex-shrink-0"></i>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" itemprop="telephone"
                               class="hover:text-brand-gold transition-colors">{{ $contactPhone }}</a>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="fas fa-envelope text-brand-gold w-4 flex-shrink-0"></i>
                            <a href="mailto:{{ $contactEmail }}" itemprop="email"
                               class="hover:text-brand-gold transition-colors break-all">{{ $contactEmail }}</a>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="fas fa-globe text-brand-gold w-4 flex-shrink-0"></i>
                            <a href="https://www.obiikriationz.com" target="_blank" rel="noopener noreferrer"
                               class="hover:text-brand-gold transition-colors">obiikriationz.com</a>
                        </li>
                    </ul>
                    <div class="mt-6">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}"
                           class="inline-flex items-center gap-2 bg-brand-gold hover:bg-yellow-400 text-brand-dark font-bold text-sm px-5 py-3 rounded-full transition-all">
                            <i class="fas fa-microphone text-xs"></i>
                            Be a Guest
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/10 px-6 py-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <div class="text-center md:text-left">
                    <p>&copy; {{ date('Y') }} <strong class="text-gray-400">Business Giseness</strong>. All rights reserved.</p>
                    <p class="mt-1">A bilingual founder podcast show hosted by <a href="https://www.abhiramchandramohan.com/" target="_blank" rel="noopener noreferrer" class="text-brand-gold hover:text-yellow-400 transition-colors">Abhiram Chandramohan</a>. Produced by <a href="https://www.obiikriationz.com" target="_blank" rel="noopener noreferrer" class="text-brand-gold hover:text-yellow-400 transition-colors">Obii Kriationz Web LLP</a>, Bengaluru.</p>
                </div>
                <nav aria-label="Legal links" class="flex items-center gap-5 flex-wrap justify-center">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-brand-gold transition-colors whitespace-nowrap">Privacy Policy</a>
                    <a href="{{ route('terms-of-use') }}" class="hover:text-brand-gold transition-colors whitespace-nowrap">Terms of Use</a>
                    <a href="{{ route('disclaimer') }}" class="hover:text-brand-gold transition-colors whitespace-nowrap">Disclaimer</a>
                </nav>
            </div>
        </div>

    </footer>

    <!-- Smooth scrolling for anchor links -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
