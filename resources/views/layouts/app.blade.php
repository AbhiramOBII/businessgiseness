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
    
    {{-- Default SEO from Admin > Settings; pages override these with their own sections --}}
    @php
        $seoTitle       = \App\Models\SiteSetting::get('seo_site_title') ?: \App\Support\Seo::DEFAULT_TITLE;
        $seoDescription = \App\Models\SiteSetting::get('seo_meta_description') ?: \App\Support\Seo::metaDescription();
        $seoRobots      = \App\Models\SiteSetting::get('seo_robots') ?: 'index, follow';
        $defaultOgImage = \App\Models\SiteSetting::get('seo_default_og_image_url') ?: asset('images/bg-icon.png');
        $ga4Id          = \App\Models\SiteSetting::get('seo_ga4_id');
        $gscVerify      = \App\Models\SiteSetting::get('seo_gsc_verification');

        // Allow large image/video previews in search whenever the page is indexable
        if (str_starts_with($seoRobots, 'index')) {
            $seoRobots .= ', max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        }
    @endphp

    <title>@yield('title', $seoTitle)</title>

    <!-- Meta Description -->
    <meta name="description" content="@yield('meta-description', $seoDescription)">
    <meta name="keywords" content="@yield('meta_keywords', 'Kannada business podcast, Kannada podcast, business giseness, ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್, Karnataka entrepreneurs, Bengaluru founders, startup stories in Kannada, founder interviews')">
    <meta name="author" content="Abhiram Chandramohan">
    <meta name="robots" content="{{ $seoRobots }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', $seoTitle)">
    <meta property="og:description" content="@yield('og_description', $seoDescription)">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:site_name" content="Business Giseness">
    <meta property="og:locale" content="en_IN">
    <meta property="og:locale:alternate" content="kn_IN">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', $seoTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $seoDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $defaultOgImage)">
    <meta name="twitter:creator" content="@abhiramchandram">
    <meta name="twitter:site" content="@businessgiseness">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    
    <!-- Sitemap discovery -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    
    <!-- Theme colour (mobile browser chrome & PWA) -->
    <meta name="theme-color" content="#ba933e">
    
    @if($gscVerify)
    <meta name="google-site-verification" content="{{ $gscVerify }}">
    @endif

    <!-- AI / LLM-readable summary of the site -->
    <link rel="alternate" type="text/markdown" title="Business Giseness for AI assistants" href="{{ url('/llms.txt') }}">

    <!-- Sitewide entity graph (Organization, WebSite, PodcastSeries, host) -->
    <script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::graph()) !!}</script>

    <!-- Additional Head Content -->
    @stack('head')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300..900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles

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
            font-family: 'Figtree', system-ui, sans-serif;
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
    @include('layouts.footer')

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

    @if($ga4Id ?? false)
    <!-- Google Analytics GA4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $ga4Id }}');
    </script>
    @endif
</body>
</html>
