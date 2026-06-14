<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'short_description',
        'thumbnail',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'is_published',
        'published_at',
        'sort_order',
        'views_count',
        'reading_time',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $dates = [
        'published_at',
    ];

    // Boot method for auto-generating slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blogPost) {
            if (empty($blogPost->slug)) {
                $blogPost->slug = Str::slug($blogPost->title);
            }
            
            // Auto-generate reading time based on description
            if (empty($blogPost->reading_time) && !empty($blogPost->description)) {
                $wordCount = str_word_count(strip_tags($blogPost->description));
                $blogPost->reading_time = max(1, ceil($wordCount / 200)); // Average reading speed: 200 words per minute
            }
        });

        static::updating(function ($blogPost) {
            if ($blogPost->isDirty('title') && empty($blogPost->getOriginal('slug'))) {
                $blogPost->slug = Str::slug($blogPost->title);
            }
            
            // Update reading time if description changes
            if ($blogPost->isDirty('description')) {
                $wordCount = str_word_count(strip_tags($blogPost->description));
                $blogPost->reading_time = max(1, ceil($wordCount / 200));
            }
        });
    }

    // Route model binding by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('published_at', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        
        // Default thumbnail
        return asset('images/default-blog-thumbnail.jpg');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('M d, Y') : null;
    }

    public function getReadingTimeTextAttribute()
    {
        if (!$this->reading_time) {
            return null;
        }
        
        return $this->reading_time . ' min read';
    }

    // SEO Accessors
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: $this->short_description;
    }

    public function getOgTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getOgDescriptionAttribute($value)
    {
        return $value ?: $this->short_description;
    }

    public function getOgImageAttribute($value)
    {
        return $value ?: $this->thumbnail_url;
    }

    public function getTwitterTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getTwitterDescriptionAttribute($value)
    {
        return $value ?: $this->short_description;
    }

    public function getTwitterImageAttribute($value)
    {
        return $value ?: $this->thumbnail_url;
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function isPublished()
    {
        return $this->is_published && 
               $this->published_at && 
               $this->published_at->isPast();
    }

    // Get all unique categories
    public static function getCategories()
    {
        return static::select('category')
                    ->distinct()
                    ->whereNotNull('category')
                    ->pluck('category')
                    ->sort()
                    ->values();
    }
}
