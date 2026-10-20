<?php

namespace App\Http\Controllers;

use App\Models\CommunityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CommunityController extends Controller
{
    /**
     * BG Community landing page: what it is, upcoming and past meetups.
     */
    public function index()
    {
        $upcoming = CommunityEvent::published()->upcoming()->withCount('registrations')->orderBy('starts_at')->get();
        $past = CommunityEvent::published()->past()->withCount('registrations')->orderByDesc('starts_at')->limit(6)->get();

        return view('community.index', compact('upcoming', 'past'));
    }

    public function show(CommunityEvent $event)
    {
        abort_unless($event->is_published || auth()->user()?->is_admin, 404);

        $event->loadCount('registrations');

        return view('community.show', compact('event'));
    }

    public function register(Request $request, CommunityEvent $event)
    {
        abort_unless($event->is_published, 404);

        // Honeypot: real people never fill this hidden field
        if ($request->filled('website')) {
            return redirect()->route('community.show', $event)->with('registered', true);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => [
                'required', 'email:rfc', 'max:150',
                Rule::unique('community_registrations', 'email')->where('community_event_id', $event->id),
            ],
            'company' => 'required|string|max:150',
            'phone'   => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{7,30}$/'],
        ], [
            'email.unique' => 'You have already signed up for this meetup with this email.',
            'phone.regex'  => 'Please enter a valid phone number.',
        ]);

        // Re-check capacity inside a transaction so the last seats can't be double-booked
        $registered = DB::transaction(function () use ($event, $validated, $request) {
            $event = CommunityEvent::whereKey($event->id)->lockForUpdate()->first();

            if (!$event->acceptsRegistrations()) {
                return false;
            }

            $event->registrations()->create($validated + [
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]);

            return true;
        });

        if (!$registered) {
            return back()->withInput()->with('closed', true);
        }

        return redirect()->route('community.show', $event)->with('registered', true);
    }
}
