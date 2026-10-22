@extends('layouts.app')

@section('title', ($status === 'verified' ? 'Welcome to BG Community' : 'Link expired') . ' | Business Giseness')
@section('meta-description', 'BG Community membership confirmation.')

@push('head')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<section class="bg-brand-dark text-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-24 text-center">
        @if($status === 'verified')
            <span class="mx-auto w-16 h-16 rounded-full bg-green-500/15 text-green-400 flex items-center justify-center text-3xl">
                <i class="fas fa-check" aria-hidden="true"></i>
            </span>
            <p class="mt-8 text-brand-gold text-xs font-semibold uppercase tracking-[0.2em]">BG Community</p>
            <h1 class="mt-3 text-3xl md:text-5xl font-black leading-tight">You're in, {{ $member->name }}!</h1>
            <p class="mt-6 text-lg text-white/70 leading-relaxed">
                Your email is confirmed and you're now part of BG Community. We'll be in touch about upcoming meetups and
                introductions that match what you're looking for.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('community.index') }}#meetups" class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold px-7 py-3.5 transition-colors">
                    <i class="far fa-calendar-check" aria-hidden="true"></i> See upcoming meetups
                </a>
                <a href="{{ route('episodes.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 hover:border-brand-gold text-white font-semibold px-7 py-3.5 transition-colors">
                    Watch the podcast
                </a>
            </div>
        @else
            <span class="mx-auto w-16 h-16 rounded-full bg-amber-500/15 text-amber-400 flex items-center justify-center text-3xl">
                <i class="fas fa-link-slash" aria-hidden="true"></i>
            </span>
            <h1 class="mt-8 text-3xl md:text-4xl font-black leading-tight">This link has expired or isn't valid</h1>
            <p class="mt-6 text-lg text-white/70 leading-relaxed">
                Confirmation links work for {{ \App\Models\CommunityMember::VERIFICATION_DAYS }} days. Register again with the same email
                and we'll send you a fresh link.
            </p>
            <a href="{{ route('community.index') }}#join" class="mt-10 inline-flex items-center justify-center gap-2 rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold px-7 py-3.5 transition-colors">
                Register again <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        @endif
    </div>
</section>
@endsection
