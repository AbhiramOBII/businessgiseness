<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * A BG Community meetup — networking for business owners to meet
 * potential customers, vendors and partners.
 */
class CommunityEvent extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'starts_at',
        'ends_at',
        'is_online',
        'venue_name',
        'venue_address',
        'map_link',
        'capacity',
        'cover_image',
        'registration_open',
        'is_published',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_online' => 'boolean',
        'capacity' => 'integer',
        'registration_open' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'meetup';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CommunityRegistration::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /** Events that haven't finished yet (ends_at, or the start day if no end time). */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q
            ->where('ends_at', '>=', now())
            ->orWhere(fn ($q) => $q->whereNull('ends_at')->where('starts_at', '>=', now()->startOfDay())));
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q
            ->where('ends_at', '<', now())
            ->orWhere(fn ($q) => $q->whereNull('ends_at')->where('starts_at', '<', now()->startOfDay())));
    }

    public function isPast(): bool
    {
        return ($this->ends_at ?? $this->starts_at->copy()->endOfDay())->isPast();
    }

    public function spotsLeft(): ?int
    {
        if (!$this->capacity) {
            return null;
        }

        $taken = $this->registrations_count ?? $this->registrations()->count();

        return max(0, $this->capacity - $taken);
    }

    public function isFull(): bool
    {
        return $this->spotsLeft() === 0;
    }

    public function acceptsRegistrations(): bool
    {
        return $this->is_published && $this->registration_open && !$this->isPast() && !$this->isFull();
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    /** e.g. "Sat, 15 Nov 2026 · 6:00 PM – 8:30 PM" */
    public function getWhenLabelAttribute(): string
    {
        $label = $this->starts_at->format('D, j M Y · g:i A');

        if ($this->ends_at) {
            $label .= ' – ' . ($this->ends_at->isSameDay($this->starts_at)
                ? $this->ends_at->format('g:i A')
                : $this->ends_at->format('D, j M Y · g:i A'));
        }

        return $label;
    }

    public function getWhereLabelAttribute(): string
    {
        if ($this->is_online) {
            return 'Online';
        }

        return collect([$this->venue_name, $this->venue_address])->filter()->join(', ') ?: 'Bengaluru';
    }
}
