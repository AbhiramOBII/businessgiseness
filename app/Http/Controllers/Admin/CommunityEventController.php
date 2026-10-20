<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityEvent;
use App\Models\CommunityRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CommunityEventController extends Controller
{
    public function index()
    {
        $events = CommunityEvent::withCount('registrations')->orderByDesc('starts_at')->paginate(15);
        $totalMembers = CommunityRegistration::distinct('email')->count('email');

        return view('admin.community.index', compact('events', 'totalMembers'));
    }

    public function create()
    {
        return view('admin.community.form', ['event' => new CommunityEvent([
            'registration_open' => true,
            'starts_at' => now()->addWeeks(2)->setTime(18, 0),
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = CommunityEvent::uniqueSlug(($data['slug'] ?? null) ?: $data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('community', 'public');
        }

        $event = CommunityEvent::create($data);

        return redirect()->route('admin.community-events.show', $event)->with('success', 'Meetup created.');
    }

    /**
     * Meetup details + the people who signed up.
     */
    public function show(Request $request, CommunityEvent $communityEvent)
    {
        $registrations = $communityEvent->registrations()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');
                $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $communityEvent->loadCount('registrations');

        return view('admin.community.show', ['event' => $communityEvent, 'registrations' => $registrations]);
    }

    public function edit(CommunityEvent $communityEvent)
    {
        return view('admin.community.form', ['event' => $communityEvent]);
    }

    public function update(Request $request, CommunityEvent $communityEvent)
    {
        $data = $this->validated($request, $communityEvent);
        $data['slug'] = CommunityEvent::uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $communityEvent->id);

        if ($request->hasFile('cover_image')) {
            if ($communityEvent->cover_image) {
                Storage::disk('public')->delete($communityEvent->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('community', 'public');
        } elseif ($request->boolean('remove_cover_image') && $communityEvent->cover_image) {
            Storage::disk('public')->delete($communityEvent->cover_image);
            $data['cover_image'] = null;
        }

        $communityEvent->update($data);

        return redirect()->route('admin.community-events.show', $communityEvent)->with('success', 'Meetup updated.');
    }

    public function destroy(CommunityEvent $communityEvent)
    {
        if ($communityEvent->cover_image) {
            Storage::disk('public')->delete($communityEvent->cover_image);
        }

        $communityEvent->delete();

        return redirect()->route('admin.community-events.index')->with('success', 'Meetup and its sign-ups deleted.');
    }

    public function export(CommunityEvent $communityEvent)
    {
        $filename = 'bg-community-' . $communityEvent->slug . '-signups-' . now()->format('Y-m-d') . '.csv';
        $registrations = $communityEvent->registrations()->oldest()->get();

        return response()->streamDownload(function () use ($registrations) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Company', 'Phone', 'Signed up at']);
            foreach ($registrations as $r) {
                // Prefix free-text values that spreadsheets would treat as formulas
                // (phone is already restricted to digits and + - ( ) by validation)
                $row = array_map(fn ($v) => preg_match('/^[=+\-@]/', (string) $v) ? "'" . $v : $v,
                    [$r->name, $r->email, $r->company]);
                fputcsv($out, [...$row, $r->phone, $r->created_at->format('Y-m-d H:i')]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }

    public function destroyRegistration(CommunityEvent $communityEvent, CommunityRegistration $registration)
    {
        abort_unless($registration->community_event_id === $communityEvent->id, 404);

        $registration->delete();

        return back()->with('success', "Removed {$registration->name}'s sign-up.");
    }

    private function validated(Request $request, ?CommunityEvent $event = null): array
    {
        $data = $request->validate([
            'title'             => 'required|string|max:200',
            'slug'              => ['nullable', 'string', 'max:200', 'alpha_dash', Rule::unique('community_events', 'slug')->ignore($event?->id)],
            'summary'           => 'required|string|max:300',
            'description'       => 'nullable|string|max:10000',
            'starts_at'         => 'required|date',
            'ends_at'           => 'nullable|date|after:starts_at',
            'is_online'         => 'boolean',
            'venue_name'        => 'nullable|required_unless:is_online,1|string|max:200',
            'venue_address'     => 'nullable|string|max:255',
            'map_link'          => 'nullable|url|max:500',
            'capacity'          => 'nullable|integer|min:1|max:100000',
            'cover_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'registration_open' => 'boolean',
            'is_published'      => 'boolean',
        ], [
            'venue_name.required_unless' => 'Add a venue, or tick "Online meetup".',
        ]);

        foreach (['is_online', 'registration_open', 'is_published'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }
        unset($data['cover_image']);

        return $data;
    }
}
