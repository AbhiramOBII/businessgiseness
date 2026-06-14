{{-- ═══════════════════════════════════════════════
    TOP BAR
═══════════════════════════════════════════════ --}}
<div class="bg-[#38344A] text-gray-300 text-sm py-2">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

        {{-- Contact --}}
        <div class="flex items-center gap-6">
            <a href="mailto:info@obiikriationz.com" class="flex items-center gap-2 hover:text-brand-gold transition-colors">
                <i class="fas fa-envelope"></i>
                <span class="hidden sm:inline">info@obiikriationz.com</span>
            </a>
            <a href="tel:+919964102103" class="flex items-center gap-2 hover:text-brand-gold transition-colors">
                <i class="fas fa-phone"></i>
                <span>+91 9964 102 103</span>
            </a>
        </div>

        {{-- Social --}}
        <div class="flex items-center gap-4">
            <a href="https://www.instagram.com/businessgisenessenglish/" target="_blank" aria-label="Instagram" class="hover:text-brand-gold transition-colors"><i class="fab fa-instagram text-base"></i></a>
            <a href="https://www.youtube.com/@BusinessGiseness" target="_blank" aria-label="YouTube" class="hover:text-brand-gold transition-colors"><i class="fab fa-youtube text-base"></i></a>
            <a href="https://creators.spotify.com/pod/profile/business-giseness/" target="_blank" aria-label="Spotify" class="hover:text-brand-gold transition-colors"><i class="fab fa-spotify text-base"></i></a>
            <a href="https://www.linkedin.com/company/business-giseness/" target="_blank" aria-label="LinkedIn" class="hover:text-brand-gold transition-colors"><i class="fab fa-linkedin text-base"></i></a>
        </div>

    </div>
</div>

{{-- Header layout media queries --}}
<style>
    #hdr-mobile   { display: grid; }
    #hdr-desktop  { display: none; }
    @media (min-width: 768px) {
        #hdr-mobile  { display: none; }
        #hdr-desktop { display: grid; }
    }
</style>

{{-- ═══════════════════════════════════════════════
    MAIN HEADER
═══════════════════════════════════════════════ --}}
<header
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    class="bg-brand-dark shadow-lg sticky top-0 z-50"
>
    {{-- ── MOBILE ROW: Logo 1 | Logo 2 | Hamburger ── --}}
    <div id="hdr-mobile"
         class="max-w-7xl mx-auto px-6 py-3"
         style="grid-template-columns:1fr 1fr auto; align-items:center;">

        <div style="display:flex; align-items:center; justify-content:flex-start;">
            <a href="/"><img src="{{ asset('images/logo-english-white-gold.png') }}"
                 alt="Business Giseness" class="h-14 w-auto object-contain"></a>
        </div>

        <div style="display:flex; align-items:center; justify-content:center;">
            <a href="/"><img src="{{ asset('images/logo-Kannada-white-gold.png') }}"
                 alt="Business Giseness Kannada" class="h-14 w-auto object-contain"></a>
        </div>

        <div style="display:flex; align-items:center; justify-content:flex-end;">
            <button @click="open = true"
                    class="text-brand-white focus:outline-none p-2"
                    aria-label="Open menu">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

    </div>

    {{-- ── DESKTOP ROW: Logo 1 | Nav | Logo 2 ── --}}
    <div id="hdr-desktop"
         class="max-w-7xl mx-auto px-6 py-3"
         style="grid-template-columns:20% 60% 20%; align-items:center;">

        <div style="display:flex; align-items:center; justify-content:flex-start;">
            <a href="/"><img src="{{ asset('images/logo-english-white-gold.png') }}"
                 alt="Business Giseness" class="h-16 w-auto object-contain"></a>
        </div>

        <div style="display:flex; align-items:center; justify-content:center; gap:28px;">
            <a href="{{ route('about-business-giseness-podcast') }}"
               class="text-brand-white hover:text-brand-gold font-medium transition-colors">About</a>
            <a href="{{ route('episodes.index') }}"
               class="text-brand-white hover:text-brand-gold font-medium transition-colors">Episodes</a>
            <a href="{{ route('guests.index') }}"
               class="text-brand-white hover:text-brand-gold font-medium transition-colors">Guests</a>
            <a href="{{ route('blog.index') }}"
               class="text-brand-white hover:text-brand-gold font-medium transition-colors">Blog</a>
            <a href="tel:+919964102103"
               class="bg-brand-gold text-brand-dark px-5 py-2 rounded-full font-semibold hover:bg-yellow-400 transition-colors"
               style="white-space:nowrap;">Collaborate</a>
        </div>

        <div style="display:flex; align-items:center; justify-content:flex-end;">
            <a href="/"><img src="{{ asset('images/logo-Kannada-white-gold.png') }}"
                 alt="Business Giseness Kannada" class="h-16 w-auto object-contain"></a>
        </div>

    </div>

    {{-- ════════════════════════════
        MOBILE OVERLAY
    ════════════════════════════ --}}
    <div
        x-show="open"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-black/50 z-40"
        style="display:none;"
    ></div>

    {{-- ════════════════════════════
        MOBILE DRAWER
    ════════════════════════════ --}}
    <div
        x-show="open"
        x-transition:enter="transition-transform duration-300 ease-in-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-300 ease-in-out"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 h-full w-80 bg-gradient-to-b from-brand-dark to-gray-900 z-50 flex flex-col"
        style="display:none;"
    >
        {{-- Drawer Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-700">
            <img src="{{ asset('images/business-giseness-logo.webp') }}"
                 alt="Business Giseness"
                 class="h-12 w-auto object-contain">
            <button @click="open = false"
                    class="text-brand-white hover:text-brand-gold transition-colors"
                    aria-label="Close menu">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- Drawer Nav --}}
        <nav class="flex-1 px-6 py-8 space-y-6 overflow-y-auto">
            <a href="{{ route('about-business-giseness-podcast') }}" @click="open = false"
               class="flex items-center gap-4 text-brand-white hover:text-brand-gold font-medium text-lg transition-colors group">
                <i class="fas fa-info-circle text-brand-gold group-hover:scale-110 transition-transform"></i>About
            </a>
            <a href="{{ route('episodes.index') }}" @click="open = false"
               class="flex items-center gap-4 text-brand-white hover:text-brand-gold font-medium text-lg transition-colors group">
                <i class="fas fa-play-circle text-brand-gold group-hover:scale-110 transition-transform"></i>Episodes
            </a>
            <a href="{{ route('guests.index') }}" @click="open = false"
               class="flex items-center gap-4 text-brand-white hover:text-brand-gold font-medium text-lg transition-colors group">
                <i class="fas fa-users text-brand-gold group-hover:scale-110 transition-transform"></i>Guests
            </a>
            <a href="{{ route('blog.index') }}" @click="open = false"
               class="flex items-center gap-4 text-brand-white hover:text-brand-gold font-medium text-lg transition-colors group">
                <i class="fas fa-blog text-brand-gold group-hover:scale-110 transition-transform"></i>Blog
            </a>
            <a href="tel:+919964102103" @click="open = false"
               class="flex items-center gap-3 bg-brand-gold text-brand-dark px-6 py-3 rounded-full font-semibold hover:bg-yellow-400 transition-colors text-lg mt-4">
                <i class="fas fa-handshake"></i>Collaborate
            </a>
        </nav>

        {{-- Drawer Footer --}}
        <div class="px-6 py-5 border-t border-gray-700">
            <p class="text-gray-400 text-xs text-center mb-3">Follow us</p>
            <div class="flex justify-center gap-3">
                <a href="https://www.instagram.com/businessgisenessenglish/" target="_blank"
                   class="w-10 h-10 bg-gray-800 hover:bg-brand-gold rounded-lg flex items-center justify-center text-gray-400 hover:text-brand-dark transition-all">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.youtube.com/@BusinessGiseness" target="_blank"
                   class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://creators.spotify.com/pod/profile/business-giseness/" target="_blank"
                   class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                    <i class="fab fa-spotify"></i>
                </a>
                <a href="https://www.linkedin.com/company/business-giseness/" target="_blank"
                   class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
        </div>

    </div>
</header>
