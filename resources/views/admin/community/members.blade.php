@extends('admin.layout')

@section('title', 'BG Community Members')
@section('page-title', 'BG Community')
@section('page-description', 'People who registered to become part of the community')

@section('content')
@php use App\Models\CommunityMember; @endphp
<div class="space-y-6">
    @include('admin.community._tabs')

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
    @endif

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
            ['label' => 'Confirmed members', 'value' => $counts['verified'], 'status' => 'verified', 'class' => 'text-green-700'],
            ['label' => 'Awaiting email confirmation', 'value' => $counts['unverified'], 'status' => 'unverified', 'class' => 'text-amber-700'],
            ['label' => 'All registrations', 'value' => $counts['all'], 'status' => null, 'class' => 'text-gray-900'],
        ] as $card)
            <a href="{{ route('admin.community-members.index', array_filter(['status' => $card['status']])) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-brand-gold">
                <p class="text-xs uppercase tracking-wider text-gray-500">{{ $card['label'] }}</p>
                <p class="mt-1 text-2xl font-bold {{ $card['class'] }}">{{ number_format($card['value']) }}</p>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 grid gap-3 md:grid-cols-5 items-end">
        <div class="md:col-span-2">
            <label class="block text-xs text-gray-500 mb-1" for="search">Search</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Name, email, company, phone"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-gold outline-none">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1" for="vertical">Vertical</label>
            <select id="vertical" name="vertical" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                <option value="">All verticals</option>
                @foreach(CommunityMember::VERTICALS as $v)
                    <option value="{{ $v }}" @selected(request('vertical') === $v)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1" for="expects">Looking for</label>
            <select id="expects" name="expects" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                <option value="">Anything</option>
                @foreach(CommunityMember::EXPECTATIONS as $key => $label)
                    <option value="{{ $key }}" @selected(request('expects') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button class="flex-1 px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-semibold">Filter</button>
            <a href="{{ route('admin.community-members.export', request()->query()) }}" class="px-4 py-2 rounded-lg bg-brand-gold text-brand-dark text-sm font-semibold" title="Export these members">
                <i class="fas fa-file-csv"></i>
            </a>
        </div>
    </form>

    {{-- Members --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($members->count())
            <ul class="divide-y divide-gray-200">
                @foreach($members as $member)
                    <li class="p-4 sm:p-5" x-data="{ open: false }">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900">
                                    {{ $member->name }}
                                    <span class="font-normal text-gray-500">· {{ $member->company }}</span>
                                </p>
                                <p class="mt-1 text-sm text-gray-600 flex flex-wrap gap-x-4 gap-y-1">
                                    <a href="mailto:{{ $member->email }}" class="text-blue-600 hover:underline">{{ $member->email }}</a>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $member->phone) }}" class="hover:underline">{{ $member->phone }}</a>
                                    <span><i class="fas fa-industry text-gray-400 mr-1"></i>{{ $member->vertical }}</span>
                                </p>
                                @if($member->expectation_labels)
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($member->expectation_labels as $label)
                                            <span class="text-xs rounded-full bg-gray-100 text-gray-700 px-2 py-0.5">{{ $label }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                @if($member->isVerified())
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800" title="Confirmed {{ $member->email_verified_at->format('j M Y, g:i A') }}">
                                        <i class="fas fa-check-circle mr-1"></i>Confirmed
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">
                                        <i class="fas fa-hourglass-half mr-1"></i>Awaiting confirmation
                                    </span>
                                    <form action="{{ route('admin.community-members.resend', $member) }}" method="POST">
                                        @csrf
                                        <button class="text-blue-600 hover:text-blue-800" title="Re-send confirmation email"><i class="fas fa-paper-plane"></i></button>
                                    </form>
                                @endif
                                <span class="text-gray-400 whitespace-nowrap" title="Registered">{{ $member->created_at->format('j M Y') }}</span>
                                @if($member->message)
                                    <button type="button" @click="open = !open" class="text-gray-500 hover:text-gray-800" title="Show message"><i class="far fa-comment-dots"></i></button>
                                @endif
                                <form action="{{ route('admin.community-members.destroy', $member) }}" method="POST"
                                      onsubmit="return confirm('Remove {{ addslashes($member->name) }} from BG Community?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-gray-400 hover:text-red-600" title="Remove"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </div>
                        @if($member->message)
                            <p x-show="open" x-cloak class="mt-3 rounded-lg bg-gray-50 p-3 text-sm text-gray-700 whitespace-pre-line">{{ $member->message }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="px-4 py-3 border-t border-gray-200">{{ $members->links() }}</div>
        @else
            <div class="text-center py-16 px-6">
                <i class="fas fa-id-card text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900">{{ request()->hasAny(['search', 'vertical', 'expects', 'status']) ? 'No members match these filters' : 'No registrations yet' }}</h3>
                <p class="text-gray-600">People register from the <a href="{{ route('community.index') }}#join" target="_blank" class="text-blue-600 hover:underline">BG Community page</a>.</p>
            </div>
        @endif
    </div>
</div>
@endsection
