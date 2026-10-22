<?php

namespace App\Http\Controllers;

use App\Mail\CommunityMemberVerification;
use App\Models\CommunityMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * "Join BG Community" registration with email confirmation.
 */
class CommunityMemberController extends Controller
{
    public function store(Request $request)
    {
        $back = route('community.index') . '#join';

        // Honeypot: hidden field only bots fill in — pretend it worked
        if ($request->filled('website')) {
            return redirect($back)->with('member_pending', 'your inbox');
        }

        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'name'           => 'required|string|max:120',
            'email'          => 'required|email:rfc|max:150',
            'phone'          => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{7,30}$/'],
            'company'        => 'required|string|max:150',
            'vertical'       => ['required', Rule::in(CommunityMember::VERTICALS)],
            'expectations'   => 'required|array|min:1',
            'expectations.*' => [Rule::in(array_keys(CommunityMember::EXPECTATIONS))],
            'message'        => 'nullable|string|max:2000',
        ], [
            'phone.regex'           => 'Please enter a valid phone number.',
            'expectations.required' => 'Pick at least one thing you would like from BG Community.',
        ], [
            'name' => 'full name',
            'company' => 'company name',
        ]);

        $member = CommunityMember::firstWhere('email', $data['email']);

        if ($member?->isVerified()) {
            return redirect($back)->with('member_exists', true);
        }

        $member ??= new CommunityMember();
        $member->fill($data + [
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ])->save();

        // Don't resend if a link went out in the last two minutes (double clicks, impatience)
        if ($member->verification_sent_at && $member->verification_sent_at->gt(now()->subMinutes(2))) {
            return redirect($back)->with('member_pending', $member->email);
        }

        try {
            Mail::to($member->email, $member->name)->send(new CommunityMemberVerification($member));
            $member->forceFill(['verification_sent_at' => now()])->save();
        } catch (\Throwable $e) {
            Log::error('BG Community verification email failed', ['member' => $member->id, 'error' => $e->getMessage()]);

            return redirect($back)->withInput()->with('member_mail_failed', true);
        }

        return redirect($back)->with('member_pending', $member->email);
    }

    public function verify(Request $request, CommunityMember $member, string $hash)
    {
        $valid = $request->hasValidSignature() && hash_equals(sha1(strtolower($member->email)), $hash);

        if (!$valid) {
            return response()->view('community.verify-result', ['status' => 'invalid'], 403);
        }

        if (!$member->isVerified()) {
            $member->forceFill(['email_verified_at' => now()])->save();
        }

        return view('community.verify-result', ['status' => 'verified', 'member' => $member]);
    }
}
