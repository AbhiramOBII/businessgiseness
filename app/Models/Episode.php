<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Episode extends Model
{
    /**
     * Episode languages: code => label. Codes double as schema.org inLanguage values.
     */
    public const LANGUAGES = [
        'kn' => 'Kannada',
        'en' => 'English',
        'both' => 'Kannada & English',
    ];

    protected $fillable = [
        'title',
        'language',
        'slug',
        'thumbnail',
        'youtube_link',
        'short_description',
        'long_description',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    /**
     * Generate slug from title
     */
    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope for published episodes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }

    /**
     * Get formatted publish date
     */
    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('M d, Y') : null;
    }

    /**
     * Scope for episodes in a language (bilingual episodes match both)
     */
    public function scopeInLanguage($query, string $language)
    {
        return $query->whereIn('language', [$language, 'both']);
    }

    /**
     * Get the human-readable language label
     */
    public function getLanguageLabelAttribute(): ?string
    {
        return self::LANGUAGES[$this->language] ?? null;
    }

    /**
     * Get the schema.org inLanguage value(s)
     */
    public function getSchemaLanguageAttribute(): array|string|null
    {
        return match ($this->language) {
            'kn' => 'kn',
            'en' => 'en',
            'both' => ['kn', 'en'],
            default => null,
        };
    }

    /**
     * Get the 11-character YouTube video ID from the episode link
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if ($this->youtube_link && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|shorts\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_link, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get the best available image: uploaded thumbnail, else the YouTube thumbnail
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        return $this->youtube_id ? "https://i.ytimg.com/vi/{$this->youtube_id}/hqdefault.jpg" : null;
    }

    /**
     * Get the guests that appear in this episode.
     */
    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'episode_guest')
                    ->withPivot(['sort_order', 'is_host'])
                    ->withTimestamps()
                    ->orderByPivot('sort_order');
    }

    /**
     * Get only the hosts for this episode.
     */
    public function hosts(): BelongsToMany
    {
        return $this->guests()->wherePivot('is_host', true);
    }

    /**
     * Get only the non-host guests for this episode.
     */
    public function nonHostGuests(): BelongsToMany
    {
        return $this->guests()->wherePivot('is_host', false);
    }

    /**
     * Get the featured guests for this episode.
     */
    public function featuredGuests(): BelongsToMany
    {
        return $this->guests()->where('guests.is_featured', true);
    }

    /**
     * Check if this episode has any guests.
     */
    public function hasGuests(): bool
    {
        return $this->guests()->exists();
    }

    /**
     * Get the total number of guests in this episode.
     */
    public function getGuestCountAttribute(): int
    {
        return $this->guests()->count();
    }

    /**
     * Get the primary host (first host by sort order).
     */
    public function getPrimaryHostAttribute(): ?Guest
    {
        return $this->hosts()->first();
    }

    /**
     * Get a comma-separated list of guest names.
     */
    public function getGuestNamesAttribute(): string
    {
        return $this->guests()->pluck('name')->join(', ');
    }
}
