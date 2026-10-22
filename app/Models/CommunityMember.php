<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

/**
 * Someone who registered to become part of BG Community. They only count as
 * a member once they confirm their email via the signed verification link.
 */
class CommunityMember extends Model
{
    /** Industry options shown on the registration form. */
    public const VERTICALS = [
        'Manufacturing',
        'Retail & E-commerce',
        'Food & Beverage',
        'Real Estate & Construction',
        'Education & Training',
        'Healthcare & Wellness',
        'Technology & IT Services',
        'Professional Services (Legal, Finance, Consulting)',
        'Marketing, Media & Creative',
        'Hospitality & Travel',
        'Agriculture & Agritech',
        'Fashion & Lifestyle',
        'Logistics & Supply Chain',
        'Other',
    ];

    /** "What do you expect from BG Community?" checkboxes: key => label. */
    public const EXPECTATIONS = [
        'customers'  => 'Find new customers',
        'vendors'    => 'Find vendors & suppliers',
        'partners'   => 'Find partners & collaborators',
        'events'     => 'Attend networking meetups',
        'learning'   => 'Learn from other founders',
        'mentorship' => 'Mentorship & guidance',
        'podcast'    => 'Share my story on the podcast',
        'funding'    => 'Investor & funding connections',
    ];

    public const VERIFICATION_DAYS = 7;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'vertical',
        'expectations',
        'message',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expectations' => 'array',
        'email_verified_at' => 'datetime',
        'verification_sent_at' => 'datetime',
    ];

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('email_verified_at');
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /** Human labels for the stored expectation keys. */
    public function getExpectationLabelsAttribute(): array
    {
        return array_values(array_intersect_key(self::EXPECTATIONS, array_flip($this->expectations ?? [])));
    }

    /**
     * Signed, expiring link that confirms this email address. The email hash in
     * the URL means a link stops working if the address on file changes.
     */
    public function verificationUrl(): string
    {
        return URL::temporarySignedRoute(
            'community.members.verify',
            now()->addDays(self::VERIFICATION_DAYS),
            ['member' => $this->id, 'hash' => sha1(strtolower($this->email))]
        );
    }
}
