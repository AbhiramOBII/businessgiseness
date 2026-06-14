@extends('layouts.app')

@section('title', 'About Business Giseness — Bilingual Founder Podcast in English & Kannada')
@section('meta-description', 'Meet Abhiram Chandramohan, host of Business Giseness — the bilingual founder podcast in English & Kannada that celebrates the real, raw stories behind building a business.')
@section('meta_keywords', 'about business giseness, Abhiram Chandramohan, bilingual podcast, founder podcast, Kannada podcast, entrepreneurship podcast, Bengaluru podcast')
@section('og_type', 'profile')
@section('og_title', 'About Business Giseness — Bilingual Founder Podcast')
@section('og_description', 'Meet Abhiram Chandramohan, host of Business Giseness — celebrating real founder stories in English and Kannada.')
@section('canonical_url', route('about-business-giseness-podcast'))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Person',
    '@id'      => route('about-business-giseness-podcast') . '#host',
    'name'     => 'Abhiram Chandramohan',
    'jobTitle' => 'Podcast Host',
    'description' => 'Abhiram Chandramohan is the host and founder of Business Giseness, a bilingual podcast that celebrates raw, unfiltered founder stories in English and Kannada.',
    'url'      => route('about-business-giseness-podcast'),
    'image'    => asset('images/Abhiram-photo-03.jpg'),
    'worksFor' => [
        '@type' => 'Organization',
        'name'  => 'Business Giseness',
        'url'   => route('home')
    ],
    'sameAs' => array_filter([
        \App\Models\SiteSetting::get('social_youtube'),
        \App\Models\SiteSetting::get('social_spotify'),
        \App\Models\SiteSetting::get('social_instagram'),
        \App\Models\SiteSetting::get('social_linkedin'),
    ])
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'PodcastSeries',
    'name'        => 'Business Giseness',
    'description' => 'A bilingual podcast show in English and Kannada featuring real founder stories, honest conversations about business, struggle, growth and the journey behind building something meaningful.',
    'url'         => route('episodes.index'),
    'inLanguage'  => ['en', 'kn'],
    'author'      => [
        '@type' => 'Person',
        'name'  => 'Abhiram Chandramohan',
        '@id'   => route('about-business-giseness-podcast') . '#host',
    ],
    'webFeed' => route('home'),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div class="min-h-screen">

    {{-- ─── Hero ─────────────────────────────────────────────── --}}
    <section class="bg-brand-dark py-24 lg:py-36">
        <div class="max-w-5xl mx-auto px-6">
            <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest mb-6">
                <span class="w-6 h-px bg-brand-gold inline-block"></span>
                About Business Giseness
            </span>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight mb-8">
                The Real Stories Behind<br>
                <span class="text-brand-gold">Building a Business</span>
            </h1>
            <p class="text-gray-400 text-xl leading-relaxed max-w-3xl">
                Business Giseness was born from a simple realization — the world celebrates success far too late. The spotlight usually arrives after someone has "made it." The struggle, the grit, the resilience, the chaos — all of it gets erased in the final highlight reel.
            </p>
            <p class="text-brand-gold font-semibold text-xl mt-6">
                Business Giseness exists to change that narrative.
            </p>
        </div>
    </section>

    {{-- ─── Purpose ──────────────────────────────────────────── --}}
    <section class="bg-white py-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5">
                    <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest mb-4">
                        <span class="w-6 h-px bg-brand-gold inline-block"></span>
                        Our Purpose
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-brand-dark leading-snug">
                        Built for the ones still in the trenches.
                    </h2>
                </div>
                <div class="lg:col-span-7 space-y-5 text-gray-600 text-lg leading-relaxed">
                    <p>
                        Here, we celebrate the <span class="text-brand-dark font-semibold">builders who are still building</span> — the founders who are fighting the good fight every day, the entrepreneurs whose stories deserve to be heard long before fame, funding, or validation arrives.
                    </p>
                    <p>
                        We are a <span class="text-brand-dark font-semibold">bilingual podcast show</span>, telling founder stories in English and Kannada — making business conversations accessible to a wider audience across Karnataka and beyond.
                    </p>
                    <p class="text-brand-dark font-bold text-xl">
                        This platform is for the ones who haven't "arrived," but are <span class="text-brand-gold">relentlessly on their way.</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── Host Profile ──────────────────────────────────────── --}}
    <section class="bg-brand-dark py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                {{-- Col 5: Image --}}
                <div class="lg:col-span-5">
                    <div class="lg:sticky lg:top-28">
                        <div class="relative">
                            <div class="absolute -top-3 -left-3 w-full h-full border-2 border-brand-gold rounded-2xl opacity-30"></div>
                            <img
                                src="{{ asset('images/Abhiram-photo-03.jpg') }}"
                                alt="Abhiram Chandramohan — Host of Business Giseness"
                                class="relative w-full object-cover rounded-2xl"
                                style="aspect-ratio:4/5; object-position:top;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="relative w-full bg-brand-gold rounded-2xl flex items-center justify-center" style="display:none; aspect-ratio:4/5;">
                                <span class="text-brand-dark font-black text-6xl">AC</span>
                            </div>
                        </div>
                        <div class="mt-6 p-5 border border-white/10 rounded-xl">
                            <p class="text-white font-bold text-lg">Abhiram Chandramohan</p>
                            <p class="text-brand-gold text-sm mt-1">Founder &amp; Host, Business Giseness</p>
                            <p class="text-gray-500 text-xs mt-1">Founder &amp; Managing Partner, Obii Kriationz Web LLP</p>
                            <div class="flex gap-3 mt-4">
                                <a href="https://www.linkedin.com/in/abhiram-chandramohan/" target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 bg-white/5 hover:bg-brand-gold rounded-lg flex items-center justify-center text-gray-400 hover:text-brand-dark transition-all">
                                    <i class="fab fa-linkedin text-sm"></i>
                                </a>
                                <a href="https://www.youtube.com/@abhiramChandramohan" target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 bg-white/5 hover:bg-red-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                    <i class="fab fa-youtube text-sm"></i>
                                </a>
                                <a href="https://www.instagram.com/abhiramchandramohan/" target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 bg-white/5 hover:bg-pink-500 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                    <i class="fab fa-instagram text-sm"></i>
                                </a>
                                <a href="https://www.abhiramchandramohan.com/" target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 bg-white/5 hover:bg-blue-500 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                    <i class="fas fa-globe text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Col 7: Text --}}
                <div class="lg:col-span-7 space-y-7">
                    <div>
                        <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                            <span class="w-6 h-px bg-brand-gold inline-block"></span>
                            About the Host
                        </span>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mt-3">
                            Abhiram Chandramohan
                        </h2>
                        <p class="text-gray-500 mt-1">Entrepreneur · Storyteller · Builder of Builders</p>
                    </div>

                    <p class="text-gray-300 text-lg leading-relaxed">
                        Abhiram Chandramohan is the <span class="text-brand-gold font-semibold">Founder and Host of Business Giseness</span> — a bilingual podcast show bringing real founder stories to life in English and Kannada.
                    </p>

                    <p class="text-gray-300 text-lg leading-relaxed">
                        A first-generation entrepreneur, he is also the <span class="text-white font-medium">Founder &amp; Managing Partner of Obii Kriationz Web LLP</span>, a Bengaluru-based technology, digital marketing and content company. With over 14 years of experience in building web platforms, digital products and business solutions, he has worked with entrepreneurs, institutions, startups and growing businesses across sectors.
                    </p>

                    <blockquote class="border-l-4 border-brand-gold pl-6 py-1">
                        <p class="text-white text-xl italic leading-relaxed">
                            "People see the outcome of entrepreneurship, but no one talks about the process."
                        </p>
                    </blockquote>

                    <p class="text-gray-300 text-lg leading-relaxed">
                        As a host, Abhiram's strength lies in asking <span class="text-white font-medium">simple, honest and deeply human questions</span> that help guests open up about the business behind the brand and the person behind the founder.
                    </p>

                    <div class="border-l-4 border-brand-gold pl-6 py-1">
                        <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-2">His Mission</p>
                        <p class="text-white text-lg leading-relaxed">
                            To document inspiring founder journeys and make business stories more <span class="text-brand-gold font-semibold">relatable, practical and accessible</span> to the next generation of entrepreneurs.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ─── Vision ────────────────────────────────────────────── --}}
    <section class="bg-white py-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-14">
                <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                    <span class="w-6 h-px bg-brand-gold inline-block"></span>
                    The Vision
                    <span class="w-6 h-px bg-brand-gold inline-block"></span>
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-brand-dark mt-4 leading-snug">
                    To redefine how India sees <span class="text-brand-gold">entrepreneurship.</span>
                </h2>
            </div>
            <p class="text-gray-600 text-xl leading-relaxed text-center max-w-4xl mx-auto">
                Business Giseness aims to give voice to founders who rarely get a platform — the <span class="text-brand-dark font-semibold">small business owners, creators, local entrepreneurs, industry specialists, and everyday dreamers</span> who are quietly building remarkable things. We want to build India's most <span class="text-brand-dark font-semibold">authentic business storytelling ecosystem</span> — raw, real, and relatable.
            </p>
        </div>
    </section>

    {{-- ─── Mission Pillars ───────────────────────────────────── --}}
    <section class="bg-brand-dark">
        <div class="max-w-5xl mx-auto px-6 py-6">
            <div class="border-b border-white/10 py-14 text-center">
                <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                    <span class="w-6 h-px bg-brand-gold inline-block"></span>
                    The Mission
                    <span class="w-6 h-px bg-brand-gold inline-block"></span>
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mt-4">
                    Four pillars that drive <span class="text-brand-gold">everything we do</span>
                </h2>
            </div>
        </div>
        <div class="max-w-5xl mx-auto">
            <div class="divide-y divide-white/10">
                <div class="flex items-start gap-8 px-6 py-10 hover:bg-white/5 transition-colors">
                    <span class="text-brand-gold font-black text-4xl leading-none w-12 flex-shrink-0">01</span>
                    <div>
                        <h3 class="text-white font-bold text-xl mb-3">Storytelling with purpose</h3>
                        <p class="text-gray-400 leading-relaxed">
                            To create a space where entrepreneurs speak honestly about struggle, growth, failures, breakthroughs, and the emotional reality of building a business.
                        </p>
                    </div>
                </div>
                <div class="flex items-start gap-8 px-6 py-10 hover:bg-white/5 transition-colors">
                    <span class="text-brand-gold font-black text-4xl leading-none w-12 flex-shrink-0">02</span>
                    <div>
                        <h3 class="text-white font-bold text-xl mb-3">Celebrate the work-in-progress entrepreneur</h3>
                        <p class="text-gray-400 leading-relaxed">
                            To shift the focus from success stories to building stories — the phase where most people give up, but real character is formed.
                        </p>
                    </div>
                </div>
                <div class="flex items-start gap-8 px-6 py-10 hover:bg-white/5 transition-colors">
                    <span class="text-brand-gold font-black text-4xl leading-none w-12 flex-shrink-0">03</span>
                    <div>
                        <h3 class="text-white font-bold text-xl mb-3">Inspire &amp; educate through real experiences</h3>
                        <p class="text-gray-400 leading-relaxed">
                            To provide actionable takeaways for anyone dreaming of starting up, through lived experiences instead of generic advice.
                        </p>
                    </div>
                </div>
                <div class="flex items-start gap-8 px-6 py-10 hover:bg-white/5 transition-colors">
                    <span class="text-brand-gold font-black text-4xl leading-none w-12 flex-shrink-0">04</span>
                    <div>
                        <h3 class="text-white font-bold text-xl mb-3">Build a community of builders</h3>
                        <p class="text-gray-400 leading-relaxed">
                            A tribe of listeners, founders, creators, and thinkers who believe in authenticity, resilience, and progress over perfection.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── Why It Matters ────────────────────────────────────── --}}
    <section class="bg-white py-20">
        <div class="max-w-4xl mx-auto px-6">
            <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest mb-8 block text-center justify-center flex">
                <span class="w-6 h-px bg-brand-gold inline-block"></span>
                Why It Matters
                <span class="w-6 h-px bg-brand-gold inline-block"></span>
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-brand-dark text-center mb-10">
                Entrepreneurship is not just boardrooms and big exits.
            </h2>
            <div class="bg-brand-dark rounded-2xl overflow-hidden">
                <div class="px-8 md:px-12 py-10">
                    <p class="text-gray-300 text-xl leading-relaxed text-center mb-6">
                        It's early mornings, late nights, fear, courage, rejection, hope, clarity, confusion, execution, and belief.
                    </p>
                </div>
                <div class="bg-brand-gold px-8 md:px-12 py-6 text-center">
                    <p class="text-brand-dark font-bold text-lg md:text-xl">
                        Business Giseness captures that truth — the <em>gisu</em> behind every business.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── CTA ────────────────────────────────────────────────── --}}
    <section class="bg-brand-dark">
        <div class="border-b border-white/10 py-20 px-6">
            <div class="max-w-5xl mx-auto flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
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
                    Whether you're a founder, educator, creator or changemaker — if you have a real journey to share, we want to tell it.
                </p>
            </div>
        </div>
        <div class="py-12 px-6">
            <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
                <p class="text-gray-400 text-base">Ready to be on the show? Let's talk.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="tel:+919964102103"
                       class="inline-flex items-center justify-center gap-2 bg-brand-gold hover:bg-yellow-400 text-brand-dark font-bold px-8 py-4 rounded-full transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-phone text-sm"></i>
                        +91 9964 102 103
                    </a>
                    <a href="mailto:info@obiikriationz.com"
                       class="inline-flex items-center justify-center gap-2 border border-white/20 hover:border-brand-gold text-white hover:text-brand-gold font-semibold px-8 py-4 rounded-full transition-all duration-300">
                        <i class="fas fa-envelope text-sm"></i>
                        Send a Message
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
