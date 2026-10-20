{{--
    Visible FAQ + matching FAQPage schema. Answers come from App\Support\Seo::faqs()
    so the page, the schema and /llms.txt always say the same thing.
    Optional: $heading, $intro, $theme ('light' | 'dark')
--}}
@php
    $faqs  = \App\Support\Seo::faqs();
    $dark  = ($theme ?? 'light') === 'dark';
@endphp

@push('head')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::faqSchema($faqs)) !!}</script>
@endpush

<section id="faq" class="{{ $dark ? 'bg-brand-dark text-white' : 'bg-gray-50' }} py-20" aria-labelledby="faq-heading">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 text-brand-gold font-semibold text-xs uppercase tracking-widest">
                <span class="w-6 h-px bg-brand-gold inline-block"></span>
                FAQ
                <span class="w-6 h-px bg-brand-gold inline-block"></span>
            </span>
            <h2 id="faq-heading" class="text-3xl md:text-4xl font-bold mt-3 {{ $dark ? 'text-white' : 'text-brand-dark' }}">
                {!! $heading ?? 'Questions about <span class="text-brand-gold">Business Giseness</span>' !!}
            </h2>
            @isset($intro)
                <p class="mt-4 text-lg {{ $dark ? 'text-gray-400' : 'text-gray-600' }}">{{ $intro }}</p>
            @endisset
        </div>

        <div class="space-y-3">
            @foreach($faqs as $faq)
                <details class="group rounded-xl border {{ $dark ? 'border-white/10 bg-white/[0.03]' : 'border-gray-200 bg-white' }} open:shadow-sm" @if($loop->first) open @endif>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-semibold {{ $dark ? 'text-white' : 'text-brand-dark' }} [&::-webkit-details-marker]:hidden">
                        <h3 class="text-base md:text-lg">{{ $faq['q'] }}</h3>
                        <span class="flex-shrink-0 w-7 h-7 rounded-full border border-brand-gold/50 text-brand-gold flex items-center justify-center transition-transform group-open:rotate-45" aria-hidden="true">
                            <i class="fas fa-plus text-xs"></i>
                        </span>
                    </summary>
                    <div class="px-5 pb-5 leading-relaxed {{ $dark ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ $faq['a'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
