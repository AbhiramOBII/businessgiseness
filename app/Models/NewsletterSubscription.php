<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'subscribed_at',
        'unsubscribed_at',
        'subscription_source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get inactive subscriptions
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to get subscriptions by source
     */
    public function scopeBySource(Builder $query, string $source): Builder
    {
        return $query->where('subscription_source', $source);
    }

    /**
     * Scope to get recent subscriptions
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('subscribed_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Get formatted subscription date
     */
    public function getFormattedSubscribedAtAttribute(): string
    {
        return $this->subscribed_at ? $this->subscribed_at->format('M d, Y') : '';
    }

    /**
     * Get formatted unsubscription date
     */
    public function getFormattedUnsubscribedAtAttribute(): string
    {
        return $this->unsubscribed_at ? $this->unsubscribed_at->format('M d, Y') : '';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Unsubscribed';
    }

    /**
     * Unsubscribe the user
     */
    public function unsubscribe(): bool
    {
        return $this->update([
            'is_active' => false,
            'unsubscribed_at' => Carbon::now(),
        ]);
    }

    /**
     * Resubscribe the user
     */
    public function resubscribe(): bool
    {
        return $this->update([
            'is_active' => true,
            'unsubscribed_at' => null,
        ]);
    }

    /**
     * Check if email already exists
     */
    public static function emailExists(string $email): bool
    {
        return static::where('email', $email)->exists();
    }

    /**
     * Get subscription statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => static::count(),
            'active' => static::active()->count(),
            'inactive' => static::inactive()->count(),
            'recent' => static::recent(30)->count(),
            'today' => static::whereDate('subscribed_at', Carbon::today())->count(),
        ];
    }
}
