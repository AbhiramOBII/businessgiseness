@extends('layouts.app')

@section('title', 'Business Giseness - Founder-First Storytelling Podcast')
@section('meta-description', 'Dive deep into real, raw, unfiltered stories of entrepreneurs who are still building. Business Giseness celebrates founder-first narratives with authentic business insights.')
@section('meta_keywords', 'business podcast, entrepreneur stories, startup podcast, founder interviews, business giseness, entrepreneurship, business strategy, startup stories')

@push('head')
<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
@php
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Business Giseness',
    'alternateName' => 'Business Giseness Podcast',
    'url' => url('/'),
    'description' => 'Founder-first storytelling podcast featuring real, raw, unfiltered stories of entrepreneurs who are still building their businesses.',
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Business Giseness',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/business-giseness-logo.webp')
        ]
    ],
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/') . '?search={search_term_string}',
        'query-input' => 'required name=search_term_string'
    ]
];
@endphp
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-brand-dark via-gray-900 to-brand-dark text-brand-white overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-10 w-32 h-32 border border-brand-gold rounded-full"></div>
                <div class="absolute top-40 right-20 w-28 h-28 border border-brand-gold rounded-full"></div>
                <div class="absolute bottom-32 left-1/4 w-24 h-24 border border-brand-gold rounded-full"></div>
            </div>
            
            <div class="relative container mx-auto px-6 py-24 lg:py-32">
                <div class="text-center max-w-4xl mx-auto">
                    @php
                        $heroBadge = \App\Models\SiteSetting::get('hero_badge_text', 'Now Streaming');
                        $heroLine1 = \App\Models\SiteSetting::get('hero_title_line1', 'Founder Stories');
                        $heroLine2 = \App\Models\SiteSetting::get('hero_title_line2', 'That Deserve to');
                        $heroLine3 = \App\Models\SiteSetting::get('hero_title_line3_gold', 'Be Heard');
                        $heroSubtitle = \App\Models\SiteSetting::get('hero_subtitle', 'Business Giseness is a bilingual podcast show that brings real founder stories to life.');
                        $stat1v = \App\Models\SiteSetting::get('hero_stat_1_value', '23');
                        $stat1l = \App\Models\SiteSetting::get('hero_stat_1_label', 'Episodes');
                        $stat2v = \App\Models\SiteSetting::get('hero_stat_2_value', '15.2K');
                        $stat2l = \App\Models\SiteSetting::get('hero_stat_2_label', 'Subscribers');
                        $stat3v = \App\Models\SiteSetting::get('hero_stat_3_value', '309K');
                        $stat3l = \App\Models\SiteSetting::get('hero_stat_3_label', 'Views');
                    @endphp
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-brand-gold/20 border border-brand-gold/30 rounded-full text-brand-gold text-sm font-medium mb-6">
                        <i class="fas fa-microphone mr-2"></i>
                        {{ $heroBadge }}
                    </div>
                    
                    <!-- Main Headline -->
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                        <span class="block">{{ $heroLine1 }}</span>
                        <span class="block">{{ $heroLine2 }}</span>
                        <span class="block text-brand-gold">{{ $heroLine3 }}</span>
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                        {{ $heroSubtitle }}
                    </p>
                    
                    <!-- Stats -->
                    <div class="flex flex-wrap justify-center gap-4 sm:gap-6 lg:gap-8 mb-8">
                        @if($stat1v)<div class="text-center"><div class="text-2xl font-bold text-brand-gold">{{ $stat1v }}</div><div class="text-sm text-gray-400">{{ $stat1l }}</div></div>@endif
                        @if($stat2v)<div class="text-center"><div class="text-2xl font-bold text-brand-gold">{{ $stat2v }}</div><div class="text-sm text-gray-400">{{ $stat2l }}</div></div>@endif
                        @if($stat3v)<div class="text-center"><div class="text-2xl font-bold text-brand-gold">{{ $stat3v }}</div><div class="text-sm text-gray-400">{{ $stat3l }}</div></div>@endif
                    </div>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4 sm:px-0">
                        <a href="{{ \App\Models\SiteSetting::get('social_youtube', 'https://www.youtube.com/@BusinessGiseness') }}" target="_blank" rel="noopener noreferrer" class="group bg-brand-gold text-brand-dark px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:bg-yellow-400 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl inline-block text-center">
                            <i class="fas fa-play mr-2 group-hover:animate-pulse"></i>
                            Watch Latest Episode
                        </a>
                        <a href="{{ \App\Models\SiteSetting::get('social_spotify', 'https://creators.spotify.com/pod/profile/business-giseness/') }}" target="_blank" rel="noopener noreferrer" class="group border-2 border-brand-gold text-brand-gold px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:bg-brand-gold hover:text-brand-dark transition-all duration-300 transform hover:scale-105 inline-block text-center">
                            <i class="fab fa-spotify mr-2 group-hover:animate-bounce"></i>
                            Listen on Spotify
                        </a>
                    </div>
                </div>
                
                <!-- Scroll Indicator -->
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-center">
                    <div class="text-gray-400 text-sm mb-2">Discover More</div>
                    <div class="w-6 h-10 border-2 border-gray-400 rounded-full flex justify-center items-start mx-auto">
                        <div class="w-1 h-3 bg-brand-gold rounded-full mt-2 animate-bounce"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Episodes Section -->
        <section id="episodes" class="py-20 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-14">
                        <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                            <span class="w-6 h-px bg-brand-gold inline-block"></span>
                            Fresh from the Studio
                            <span class="w-6 h-px bg-brand-gold inline-block"></span>
                        </span>
                        <h2 class="text-4xl md:text-5xl font-bold text-brand-dark mt-3 mb-4">
                            Latest <span class="text-brand-gold">Episodes</span>
                        </h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Real conversations with founders sharing their unfiltered journeys.
                        </p>
                    </div>

                    @if($latestEpisodes->count() > 0)
                        <div
                            class="relative"
                            x-data="{
                                current: 0,
                                count: {{ $latestEpisodes->count() }},
                                get slideWidth() {
                                    return window.innerWidth >= 1024 ? 33.333 : (window.innerWidth >= 768 ? 50 : 100);
                                },
                                get maxSlide() {
                                    const visible = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
                                    return Math.max(0, this.count - visible);
                                },
                                next() { this.current = this.current >= this.maxSlide ? 0 : this.current + 1; },
                                prev() { this.current = this.current <= 0 ? this.maxSlide : this.current - 1; },
                                init() { setInterval(() => this.next(), 10000); }
                            }"
                            @resize.window.debounce="current = Math.min(current, maxSlide)"
                        >
                            <div class="overflow-hidden">
                                <div
                                    class="flex transition-transform duration-500 ease-in-out"
                                    :style="\`transform: translateX(-\${current * slideWidth}%)\`"
                                >
                                    @foreach($latestEpisodes as $episode)
                                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                                            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                                <div class="aspect-video bg-gray-900 relative overflow-hidden">
                                                    @if($episode->thumbnail_url)
                                                        <img src="{{ $episode->thumbnail_url }}"
                                                             alt="{{ $episode->title }}"
                                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-brand-dark to-gray-700 flex items-center justify-center">
                                                            <div class="w-14 h-14 bg-brand-gold rounded-full flex items-center justify-center">
                                                                <i class="fas fa-play text-brand-dark text-xl"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                                        <div class="w-14 h-14 bg-brand-gold rounded-full flex items-center justify-center">
                                                            <i class="fas fa-play text-brand-dark text-lg ml-1"></i>
                                                        </div>
                                                    </div>
                                                    <div class="absolute top-3 left-3">
                                                        <span class="bg-brand-gold text-brand-dark px-3 py-1 rounded-full text-xs font-bold">
                                                            Ep {{ $episode->episode_number }}
                                                        </span>
                                                    </div>
                                                    @if($episode->views_count > 0)
                                                        <div class="absolute bottom-3 right-3">
                                                            <span class="bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs">
                                                                {{ number_format($episode->views_count) }} views
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="p-5">
                                                    <h3 class="text-lg font-bold text-brand-dark mb-2 leading-snug">{{ $episode->title }}</h3>
                                                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                                                        {{ Str::limit($episode->short_description, 90) }}
                                                    </p>
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs text-gray-400">
                                                            @if($episode->duration)
                                                                {{ $episode->duration_text }}
                                                            @else
                                                                {{ $episode->formatted_published_at }}
                                                            @endif
                                                        </span>
                                                        <a href="{{ route('episodes.show', $episode) }}"
                                                           class="bg-brand-gold hover:bg-yellow-500 text-brand-dark px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                                            Watch
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($latestEpisodes->count() > 3)
                                <button @click="prev()"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white shadow-lg rounded-full p-3 hover:bg-gray-50 transition-colors z-10">
                                    <i class="fas fa-chevron-left text-brand-dark"></i>
                                </button>
                                <button @click="next()"
                                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white shadow-lg rounded-full p-3 hover:bg-gray-50 transition-colors z-10">
                                    <i class="fas fa-chevron-right text-brand-dark"></i>
                                </button>
                            @endif
                        </div>

                        <div class="text-center mt-12">
                            <a href="{{ route('episodes.index') }}"
                               class="inline-flex items-center bg-brand-dark hover:bg-gray-800 text-white px-8 py-4 rounded-full font-bold transition-colors duration-300">
                                <i class="fas fa-play-circle mr-2"></i>
                                View All Episodes
                            </a>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-20 h-20 mx-auto mb-6 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-play text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-600 mb-3">Episodes Coming Soon</h3>
                            <p class="text-gray-500">Check back soon for our latest episodes!</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-brand-white">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto">

                    <!-- Section Header -->
                    <div class="text-center mb-14">
                        <p class="text-brand-gold font-semibold text-sm uppercase tracking-widest mb-3">About the Show</p>
                        <h2 class="text-4xl md:text-5xl font-bold text-brand-dark mb-6">
                            Welcome to <span class="text-brand-gold">Business Giseness</span>
                        </h2>
                        <div class="w-20 h-1 bg-brand-gold mx-auto"></div>
                    </div>

                    <!-- Intro Paragraphs -->
                    <div class="space-y-6 mb-12">
                        <p class="text-lg text-gray-700 leading-relaxed">
                            Business Giseness is a <span class="text-brand-gold font-semibold">bilingual podcast</span> that brings you real, unfiltered founder stories. Each episode features conversations with entrepreneurs, creators, professionals, educators, and changemakers who are building something meaningful.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            This is not a highlight reel of success. It is a deep dive into the <span class="text-brand-gold font-semibold">decisions, doubts, failures, risks, and turning points</span> that define every journey.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            Through conversations in <span class="font-semibold text-brand-dark">English and Kannada</span>, we explore the human side of business — from humble beginnings to bold ambitions, from personal struggles to professional breakthroughs.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            For aspiring entrepreneurs, business owners, students, and curious minds alike, this podcast offers a closer look into the thinking, experiences, and lessons of people building brands, companies, and communities.
                        </p>
                    </div>

                    <!-- What We Believe -->
                    <div class="bg-brand-dark rounded-2xl overflow-hidden">
                        <!-- Header strip -->
                        <div class="px-8 md:px-12 pt-10 pb-6 text-center border-b border-white/10">
                            <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                                <span class="w-6 h-px bg-brand-gold inline-block"></span>
                                What We Believe
                                <span class="w-6 h-px bg-brand-gold inline-block"></span>
                            </span>
                        </div>

                        <!-- Belief lines -->
                        <div class="divide-y divide-white/10">
                            <div class="flex items-center gap-6 px-8 md:px-12 py-6">
                                <span class="text-brand-gold font-black text-3xl md:text-4xl leading-none w-10 flex-shrink-0">01</span>
                                <p class="text-white text-xl md:text-2xl font-medium">Every business has a story.</p>
                            </div>
                            <div class="flex items-center gap-6 px-8 md:px-12 py-6">
                                <span class="text-brand-gold font-black text-3xl md:text-4xl leading-none w-10 flex-shrink-0">02</span>
                                <p class="text-white text-xl md:text-2xl font-medium">Every founder has a journey.</p>
                            </div>
                            <div class="flex items-center gap-6 px-8 md:px-12 py-6">
                                <span class="text-brand-gold font-black text-3xl md:text-4xl leading-none w-10 flex-shrink-0">03</span>
                                <p class="text-white text-xl md:text-2xl font-medium">And every journey has a lesson worth sharing.</p>
                            </div>
                        </div>

                        <!-- Tagline footer -->
                        <div class="bg-brand-gold px-8 md:px-12 py-6 text-center">
                            <p class="text-brand-dark font-bold text-base md:text-lg">
                                Welcome to Business Giseness — where founder stories become business lessons.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- About the Host Section -->
        <section class="py-20 bg-brand-dark">
            <div class="container mx-auto px-6">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                        <!-- Col 5: Image -->
                        <div class="lg:col-span-5">
                            <div class="lg:sticky lg:top-28">
                                <div class="relative">
                                    <!-- Gold accent frame -->
                                    <div class="absolute -top-3 -left-3 w-full h-full border-2 border-brand-gold rounded-2xl opacity-30"></div>
                                    <img
                                        src="{{ asset('images/Abhiram-photo-03.jpg') }}"
                                        alt="Abhiram Chandramohan - Host of Business Giseness"
                                        class="relative w-full object-cover rounded-2xl"
                                        style="aspect-ratio:4/5; object-position:top;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="relative w-full bg-brand-gold rounded-2xl flex items-center justify-center" style="display:none; aspect-ratio:4/5;">
                                        <span class="text-brand-dark font-black text-6xl">AC</span>
                                    </div>
                                </div>

                                <!-- Name plate under image -->
                                <div class="mt-6 p-5 border border-white/10 rounded-xl">
                                    <p class="text-white font-bold text-lg">Abhiram Chandramohan</p>
                                    <p class="text-brand-gold text-sm mt-1">Founder & Host, Business Giseness</p>
                                    <p class="text-gray-500 text-xs mt-1">Founder & Managing Partner, Obii Kriationz Web LLP</p>
                                    <div class="flex gap-3 mt-4">
                                        <a href="https://www.linkedin.com/in/abhiram-chandramohan/" target="_blank"
                                           class="w-9 h-9 bg-white/5 hover:bg-brand-gold rounded-lg flex items-center justify-center text-gray-400 hover:text-brand-dark transition-all">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="https://www.youtube.com/@abhiramChandramohan" target="_blank"
                                           class="w-9 h-9 bg-white/5 hover:bg-red-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                            <i class="fab fa-youtube text-sm"></i>
                                        </a>
                                        <a href="https://www.instagram.com/abhiramchandramohan/" target="_blank"
                                           class="w-9 h-9 bg-white/5 hover:bg-pink-500 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                            <i class="fab fa-instagram text-sm"></i>
                                        </a>
                                        <a href="https://www.abhiramchandramohan.com/" target="_blank"
                                           class="w-9 h-9 bg-white/5 hover:bg-blue-500 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                            <i class="fas fa-globe text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Col 7: Text -->
                        <div class="lg:col-span-7 space-y-7">
                            <!-- Section label -->
                            <div>
                                <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                                    <span class="w-6 h-px bg-brand-gold inline-block"></span>
                                    About the Host
                                </span>
                                <h2 class="text-3xl md:text-4xl font-bold text-white mt-3">
                                    Abhiram Chandramohan
                                </h2>
                            </div>

                            <p class="text-gray-300 text-lg leading-relaxed">
                                Abhiram Chandramohan is the <span class="text-brand-gold font-semibold">Founder and Host of Business Giseness</span> — a bilingual podcast show that brings real founder stories to life in English and Kannada.
                            </p>

                            <p class="text-gray-300 text-lg leading-relaxed">
                                A first-generation entrepreneur, Abhiram is also the <span class="text-white font-medium">Founder &amp; Managing Partner of Obii Kriationz Web LLP</span>, a Bengaluru-based technology, digital marketing and content company. With over 14 years of experience in building web platforms, digital products and business solutions, he has worked closely with entrepreneurs, institutions, startups and growing businesses across different sectors.
                            </p>

                            <p class="text-gray-300 text-lg leading-relaxed">
                                Through Business Giseness, Abhiram brings together his experience as a business owner, product thinker, marketer and storyteller. His conversations go beyond surface-level success stories and focus on the real journey of founders — their struggles, decisions, failures, turning points and lessons learned along the way.
                            </p>

                            <p class="text-gray-300 text-lg leading-relaxed">
                                As a host, Abhiram's strength lies in asking <span class="text-white font-medium">simple, honest and deeply human questions</span> that help guests open up about the business behind the brand and the person behind the founder.
                            </p>

                            <!-- Mission callout -->
                            <div class="border-l-4 border-brand-gold pl-6 py-1">
                                <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-2">His Mission</p>
                                <p class="text-white text-lg leading-relaxed">
                                    To document inspiring founder journeys and make business stories more <span class="text-brand-gold font-semibold">relatable, practical and accessible</span> to the next generation of entrepreneurs.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Subscribe CTA Strip -->
        <section class="py-14 bg-white border-t border-gray-100">
            <div class="container mx-auto px-6 text-center">
                <p class="text-lg text-gray-600 mb-6">Subscribe to never miss an episode</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://www.youtube.com/@BusinessGiseness" target="_blank"
                       class="bg-brand-gold hover:bg-yellow-500 text-brand-dark px-8 py-4 rounded-full font-bold text-base transition-all duration-300 transform hover:scale-105 inline-flex items-center justify-center">
                        <i class="fab fa-youtube mr-2"></i>
                        Subscribe on YouTube
                    </a>
                    <a href="https://creators.spotify.com/pod/show/business-giseness" target="_blank"
                       class="border-2 border-brand-gold text-brand-gold hover:bg-brand-gold hover:text-brand-dark px-8 py-4 rounded-full font-bold text-base transition-all duration-300 transform hover:scale-105 inline-flex items-center justify-center">
                        <i class="fab fa-spotify mr-2"></i>
                        Follow on Spotify
                    </a>
                </div>
            </div>
        </section>

        <!-- Collaborate CTA Section -->
        <section id="collaborate" class="bg-brand-dark relative overflow-hidden">

            {{-- Top: headline band --}}
            <div class="border-b border-white/10 py-20 px-6">
                <div class="max-w-5xl mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                        <div>
                            <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest mb-5">
                                <span class="w-6 h-px bg-brand-gold inline-block"></span>
                                Be a Guest
                            </span>
                            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                                Your Story<br>
                                <span class="text-brand-gold">Deserves</span> to<br>
                                Be Heard.
                            </h2>
                        </div>
                        <p class="text-gray-400 text-lg leading-relaxed max-w-md lg:text-right">
                            Are you a founder, entrepreneur, educator or creator with a real journey to share? We want to hear from you.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Middle: 3 value props --}}
            <div class="border-b border-white/10">
                <div class="max-w-5xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10">

                        <div class="px-8 py-10 group hover:bg-white/5 transition-colors">
                            <span class="text-brand-gold font-black text-3xl">01</span>
                            <h3 class="text-white font-bold text-xl mt-4 mb-3">Tell Your Real Story</h3>
                            <p class="text-gray-400 leading-relaxed text-sm">
                                Go beyond the highlight reel. Share the decisions, doubts, failures and turning points that shaped your journey.
                            </p>
                        </div>

                        <div class="px-8 py-10 group hover:bg-white/5 transition-colors">
                            <span class="text-brand-gold font-black text-3xl">02</span>
                            <h3 class="text-white font-bold text-xl mt-4 mb-3">Reach a Wider Audience</h3>
                            <p class="text-gray-400 leading-relaxed text-sm">
                                Your episode goes out across YouTube, Spotify and our bilingual community of founders, students and professionals.
                            </p>
                        </div>

                        <div class="px-8 py-10 group hover:bg-white/5 transition-colors">
                            <span class="text-brand-gold font-black text-3xl">03</span>
                            <h3 class="text-white font-bold text-xl mt-4 mb-3">Inspire the Next Builder</h3>
                            <p class="text-gray-400 leading-relaxed text-sm">
                                Your experience is someone else's roadmap. One honest conversation can change how an aspiring founder thinks.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Bottom: CTA bar --}}
            <div class="py-12 px-6">
                <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
                    <p class="text-gray-400 text-base">
                        Ready to be on the show? Let's talk.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="tel:+919964102103"
                           class="inline-flex items-center justify-center gap-2 bg-brand-gold hover:bg-yellow-400 text-brand-dark font-bold px-8 py-4 rounded-full transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-phone text-sm"></i>
                            +91 9964 102 103
                        </a>
                        <a href="mailto:hello@businessgiseness.com"
                           class="inline-flex items-center justify-center gap-2 border border-white/20 hover:border-brand-gold text-white hover:text-brand-gold font-semibold px-8 py-4 rounded-full transition-all duration-300">
                            <i class="fas fa-envelope text-sm"></i>
                            Send a Message
                        </a>
                    </div>
                </div>
            </div>

        </section>

        <!-- Blog Section -->
        <section id="blog" class="py-20 bg-brand-white">
            <div class="container mx-auto px-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center bg-brand-gold bg-opacity-10 text-white px-6 py-3 rounded-full text-sm font-semibold mb-8">
                            <i class="fas fa-pen-fancy mr-2"></i>
                            Insights & Stories
                        </div>
                        <h2 class="text-4xl md:text-5xl font-bold text-brand-dark mb-6">
                            Latest from the <span class="text-brand-gold">Blog</span>
                        </h2>
                        <div class="w-24 h-1 bg-brand-gold mx-auto mb-8"></div>
                        <p class="text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                            Deep dives into entrepreneurship, startup insights, and founder wisdom to fuel your business journey.
                        </p>
                    </div>

                    @if($latestBlogs->count() > 0)
                        <!-- Blog Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($latestBlogs as $blog)
                                <article class="bg-brand-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                                    <div class="aspect-video bg-gray-900 relative overflow-hidden">
                                        @if($blog->thumbnail_url)
                                            <img src="{{ $blog->thumbnail_url }}" 
                                                 alt="{{ $blog->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-brand-gold to-yellow-600 flex items-center justify-center">
                                                <div class="text-center text-white">
                                                    <i class="fas fa-blog text-4xl mb-4"></i>
                                                    <p class="text-lg font-semibold">{{ $blog->category }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-brand-white text-brand-gold px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $blog->category }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6">
                                        <div class="flex items-center text-sm text-gray-500 mb-3">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span>{{ $blog->formatted_published_at }}</span>
                                            @if($blog->reading_time_text)
                                                <span class="mx-2">•</span>
                                                <span>{{ $blog->reading_time_text }}</span>
                                            @endif
                                        </div>
                                        
                                        <h3 class="text-xl font-bold text-brand-dark mb-3 hover:text-brand-gold transition-colors cursor-pointer">
                                            <a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a>
                                        </h3>
                                        
                                        <p class="text-gray-600 mb-4 leading-relaxed">
                                            {{ Str::limit($blog->short_description, 120) }}
                                        </p>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-brand-gold rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-brand-dark font-semibold text-sm">BG</span>
                                                </div>
                                                <span class="text-sm text-gray-600">Business Giseness</span>
                                            </div>
                                            <a href="{{ route('blog.show', $blog) }}" class="text-brand-gold hover:text-yellow-600 font-semibold text-sm transition-colors">
                                                Read More →
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <!-- No Blog Posts State -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-blog text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4">No Blog Posts Yet</h3>
                            <p class="text-gray-600 max-w-md mx-auto">
                                We're working on bringing you insightful content. Check back soon for our latest articles!
                            </p>
                        </div>
                    @endif

                <!-- View All Blog Posts Button -->
                    <div class="text-center mt-16">
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center bg-brand-gold hover:bg-yellow-600 text-brand-dark px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-book-open mr-2"></i>
                            View All Articles
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
