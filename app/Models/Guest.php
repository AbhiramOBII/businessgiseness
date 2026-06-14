<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'photo',
        'description',
        'website',
        'twitter',
        'linkedin',
        'instagram',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            if (empty($guest->slug)) {
                $guest->slug = Str::slug($guest->name);
            }
        });

        static::updating(function ($guest) {
            if ($guest->isDirty('name') && empty($guest->slug)) {
                $guest->slug = Str::slug($guest->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the episodes that this guest appears in.
     */
    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'episode_guest')
                    ->withPivot(['sort_order', 'is_host'])
                    ->withTimestamps()
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Get published episodes only.
     */
    public function publishedEpisodes(): BelongsToMany
    {
        return $this->episodes()->where('episodes.is_published', true);
    }

    /**
     * Get episodes where this guest is the host.
     */
    public function hostedEpisodes(): BelongsToMany
    {
        return $this->episodes()->wherePivot('is_host', true);
    }

    /**
     * Get the photo URL attribute.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        if (Str::startsWith($this->photo, ['http://', 'https://'])) {
            return $this->photo;
        }

        return asset('storage/' . $this->photo);
    }

    /**
     * Get the full name with title if available.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get social media links as an array.
     */
    public function getSocialLinksAttribute(): array
    {
        $links = [];

        if ($this->website) {
            $links['website'] = [
                'url' => $this->website,
                'icon' => 'fas fa-globe',
                'label' => 'Website'
            ];
        }

        if ($this->twitter) {
            $links['twitter'] = [
                'url' => Str::startsWith($this->twitter, 'http') ? $this->twitter : 'https://twitter.com/' . ltrim($this->twitter, '@'),
                'icon' => 'fab fa-twitter',
                'label' => 'Twitter'
            ];
        }

        if ($this->linkedin) {
            $links['linkedin'] = [
                'url' => Str::startsWith($this->linkedin, 'http') ? $this->linkedin : 'https://linkedin.com/in/' . $this->linkedin,
                'icon' => 'fab fa-linkedin',
                'label' => 'LinkedIn'
            ];
        }

        if ($this->instagram) {
            $links['instagram'] = [
                'url' => Str::startsWith($this->instagram, 'http') ? $this->instagram : 'https://instagram.com/' . ltrim($this->instagram, '@'),
                'icon' => 'fab fa-instagram',
                'label' => 'Instagram'
            ];
        }

        return $links;
    }

    /**
     * Scope for featured guests.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordering by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the total number of episodes this guest has appeared in.
     */
    public function getEpisodeCountAttribute(): int
    {
        return $this->episodes()->count();
    }

    /**
     * Get the total number of published episodes this guest has appeared in.
     */
    public function getPublishedEpisodeCountAttribute(): int
    {
        return $this->publishedEpisodes()->count();
    }
}
