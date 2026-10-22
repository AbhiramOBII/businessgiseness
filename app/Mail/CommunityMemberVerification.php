<?php

namespace App\Mail;

use App\Models\CommunityMember;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommunityMemberVerification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CommunityMember $member)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirm your email to join BG Community');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.community-verify',
            text: 'emails.community-verify-text',
            with: [
                'verifyUrl' => $this->member->verificationUrl(),
                'days' => CommunityMember::VERIFICATION_DAYS,
            ],
        );
    }
}
