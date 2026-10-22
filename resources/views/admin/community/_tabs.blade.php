{{-- BG Community admin tabs: Meetups | Members --}}
@php
    $memberCount = \App\Models\CommunityMember::verified()->count();
    $tabs = [
        ['label' => 'Meetups', 'icon' => 'fa-calendar-alt', 'route' => 'admin.community-events.index', 'active' => request()->routeIs('admin.community-events.*')],
        ['label' => 'Members', 'icon' => 'fa-id-card', 'route' => 'admin.community-members.index', 'active' => request()->routeIs('admin.community-members.*'), 'badge' => $memberCount],
    ];
@endphp
<nav class="flex gap-1 border-b border-gray-200" aria-label="BG Community sections">
    @foreach($tabs as $tab)
        <a href="{{ route($tab['route']) }}"
           @if($tab['active']) aria-current="page" @endif
           class="inline-flex items-center gap-2 px-4 py-2.5 -mb-px border-b-2 text-sm font-semibold transition-colors {{ $tab['active'] ? 'border-brand-gold text-brand-dark' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
            <i class="fas {{ $tab['icon'] }}"></i>{{ $tab['label'] }}
            @isset($tab['badge'])
                <span class="text-xs rounded-full px-2 py-0.5 {{ $tab['active'] ? 'bg-brand-gold text-brand-dark' : 'bg-gray-100 text-gray-600' }}">{{ $tab['badge'] }}</span>
            @endisset
        </a>
    @endforeach
</nav>
