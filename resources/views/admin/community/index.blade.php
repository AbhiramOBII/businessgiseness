@extends('admin.layout')

@section('title', 'BG Community')
@section('page-title', 'BG Community')
@section('page-description', 'Networking meetups and the members who sign up')

@section('content')
<div class="space-y-6">
    @include('admin.community._tabs')

    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Meetups</h2>
            <p class="text-gray-600">{{ $events->total() }} {{ Str::plural('meetup', $events->total()) }} · {{ number_format($totalMembers) }} unique {{ Str::plural('member', $totalMembers) }} signed up</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('community.index') }}" target="_blank" class="text-sm text-gray-600 hover:text-gray-900">
                <i class="fas fa-external-link-alt mr-1"></i> View community page
            </a>
            <a href="{{ route('admin.community-events.create') }}"
               class="bg-brand-gold hover:bg-opacity-90 text-brand-dark px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>New Meetup
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($events->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Meetup</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">When</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Sign-ups</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <a href="{{ route('admin.community-events.show', $event) }}" class="font-semibold text-gray-900 hover:text-brand-gold">{{ $event->title }}</a>
                                    <p class="text-sm text-gray-500"><i class="fas {{ $event->is_online ? 'fa-video' : 'fa-map-marker-alt' }} mr-1"></i>{{ $event->where_label }}</p>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $event->starts_at->format('D, j M Y') }}<br>
                                    <span class="text-gray-500">{{ $event->starts_at->format('g:i A') }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if(!$event->is_published)
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">Draft</span>
                                        @elseif($event->isPast())
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500">Past</span>
                                        @elseif($event->acceptsRegistrations())
                                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">Open</span>
                                        @elseif($event->isFull())
                                            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">Full</span>
                                        @else
                                            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">Sign-ups closed</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <span class="font-semibold text-gray-900">{{ $event->registrations_count }}</span>@if($event->capacity)<span class="text-gray-500"> / {{ $event->capacity }}</span>@endif
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.community-events.show', $event) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="Sign-ups"><i class="fas fa-users"></i></a>
                                    <a href="{{ route('admin.community-events.edit', $event) }}" class="text-gray-600 hover:text-gray-900 mr-3" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('community.show', $event) }}" target="_blank" class="text-gray-600 hover:text-gray-900" title="View page"><i class="fas fa-external-link-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">{{ $events->links() }}</div>
        @else
            <div class="text-center py-16 px-6">
                <i class="fas fa-handshake text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900">No meetups yet</h3>
                <p class="text-gray-600 mb-6">Create the first BG Community meetup and share the sign-up link.</p>
                <a href="{{ route('admin.community-events.create') }}" class="bg-brand-gold text-brand-dark px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-plus mr-2"></i>New Meetup
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
