@extends('admin.layout')

@section('title', $event->title)
@section('page-title', 'BG Community — Sign-ups')
@section('page-description', $event->title)

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.community-events.index') }}" class="text-gray-600 hover:text-gray-800 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>All meetups
    </a>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Meetup summary --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                <p class="mt-1 text-gray-600"><i class="far fa-calendar mr-1"></i>{{ $event->when_label }}</p>
                <p class="text-gray-600"><i class="fas {{ $event->is_online ? 'fa-video' : 'fa-map-marker-alt' }} mr-1"></i>{{ $event->where_label }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.community-events.edit', $event) }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="{{ route('community.show', $event) }}" target="_blank" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-external-link-alt mr-1"></i>View page
                </a>
                @if($event->registrations_count)
                    <a href="{{ route('admin.community-events.export', $event) }}" class="px-4 py-2 rounded-lg bg-brand-gold text-brand-dark text-sm font-semibold">
                        <i class="fas fa-file-csv mr-1"></i>Export CSV
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Signed up</p>
                <p class="text-2xl font-bold text-gray-900">{{ $event->registrations_count }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Seats left</p>
                <p class="text-2xl font-bold text-gray-900">{{ $event->capacity ? $event->spotsLeft() : '∞' }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Status</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ !$event->is_published ? 'Draft' : ($event->isPast() ? 'Past' : ($event->acceptsRegistrations() ? 'Open' : ($event->isFull() ? 'Full' : 'Closed'))) }}
                </p>
            </div>
            <div class="rounded-lg bg-gray-50 p-4" x-data="{ copied: false }">
                <p class="text-xs uppercase tracking-wider text-gray-500">Sign-up link</p>
                <button type="button" class="text-sm font-semibold text-blue-600 hover:text-blue-800"
                        @click="navigator.clipboard.writeText('{{ route('community.show', $event) }}'); copied = true; setTimeout(() => copied = false, 2000)">
                    <i class="fas fa-link mr-1"></i><span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Sign-ups --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-900">Members signed up</h3>
            <form method="GET" class="flex gap-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, company, email, phone"
                       class="w-72 max-w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-gold outline-none">
                <button class="px-3 py-1.5 rounded-lg bg-gray-100 text-sm text-gray-700 hover:bg-gray-200">Search</button>
            </form>
        </div>

        @if($registrations->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Company</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Phone</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Signed up</th>
                            <th class="py-3 px-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $registration->name }}</td>
                                <td class="py-3 px-4 text-gray-700">{{ $registration->company }}</td>
                                <td class="py-3 px-4"><a href="mailto:{{ $registration->email }}" class="text-blue-600 hover:underline">{{ $registration->email }}</a></td>
                                <td class="py-3 px-4"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $registration->phone) }}" class="text-gray-700 hover:underline">{{ $registration->phone }}</a></td>
                                <td class="py-3 px-4 text-gray-500 whitespace-nowrap">{{ $registration->created_at->format('j M, g:i A') }}</td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.community-events.registrations.destroy', [$event, $registration]) }}" method="POST"
                                          onsubmit="return confirm('Remove {{ addslashes($registration->name) }} from this meetup?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-600" title="Remove"><i class="fas fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">{{ $registrations->links() }}</div>
        @else
            <p class="px-4 py-12 text-center text-gray-500">
                {{ request('search') ? 'No sign-ups match your search.' : 'No sign-ups yet — share the sign-up link to get started.' }}
            </p>
        @endif
    </div>
</div>
@endsection
