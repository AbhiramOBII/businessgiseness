<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CommunityMemberVerification;
use App\Models\CommunityMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommunityMemberController extends Controller
{
    public function index(Request $request)
    {
        $members = $this->filtered($request)->latest()->paginate(30)->withQueryString();

        $counts = [
            'all' => CommunityMember::count(),
            'verified' => CommunityMember::verified()->count(),
            'unverified' => CommunityMember::unverified()->count(),
        ];

        return view('admin.community.members', compact('members', 'counts'));
    }

    public function export(Request $request)
    {
        $members = $this->filtered($request)->oldest()->get();
        $filename = 'bg-community-members-' . now()->format('Y-m-d') . '.csv';
        $safe = fn ($v) => preg_match('/^[=+\-@]/', (string) $v) ? "'" . $v : $v;

        return response()->streamDownload(function () use ($members, $safe) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Phone', 'Company', 'Vertical', 'Expectations', 'Message', 'Email confirmed', 'Registered']);
            foreach ($members as $m) {
                fputcsv($out, [
                    $safe($m->name), $safe($m->email), $m->phone, $safe($m->company), $m->vertical,
                    implode('; ', $m->expectation_labels), $safe($m->message),
                    $m->email_verified_at?->format('Y-m-d H:i') ?? 'No',
                    $m->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }

    public function resend(CommunityMember $member)
    {
        if ($member->isVerified()) {
            return back()->with('success', "{$member->name} has already confirmed their email.");
        }

        try {
            Mail::to($member->email, $member->name)->send(new CommunityMemberVerification($member));
            $member->forceFill(['verification_sent_at' => now()])->save();
        } catch (\Throwable $e) {
            Log::error('BG Community verification resend failed', ['member' => $member->id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Could not send the email. Check the mail settings in .env.');
        }

        return back()->with('success', "Confirmation email re-sent to {$member->email}.");
    }

    public function destroy(CommunityMember $member)
    {
        $member->delete();

        return back()->with('success', "Removed {$member->name} from BG Community.");
    }

    private function filtered(Request $request): Builder
    {
        return CommunityMember::query()
            ->when($request->get('status') === 'verified', fn ($q) => $q->verified())
            ->when($request->get('status') === 'unverified', fn ($q) => $q->unverified())
            ->when(in_array($request->get('vertical'), CommunityMember::VERTICALS, true), fn ($q) => $q->where('vertical', $request->get('vertical')))
            ->when(array_key_exists((string) $request->get('expects'), CommunityMember::EXPECTATIONS), fn ($q) => $q->whereJsonContains('expectations', $request->get('expects')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->get('search');
                $q->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('company', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%"));
            });
    }
}
