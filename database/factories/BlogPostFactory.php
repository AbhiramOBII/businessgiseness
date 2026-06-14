<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(3, 8));
        $categories = [
            'Entrepreneurship',
            'Business Strategy',
            'Startup Stories',
            'Leadership',
            'Marketing',
            'Technology',
            'Finance',
            'Personal Development',
            'Industry Insights',
            'Success Stories'
        ];
        
        $description = fake()->paragraphs(rand(5, 12), true);
        $wordCount = str_word_count(strip_tags($description));
        $readingTime = max(1, ceil($wordCount / 200));
        
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'category' => fake()->randomElement($categories),
            'short_description' => fake()->text(200),
            'thumbnail' => null, // Will be set manually or via seeder
            'description' => $description,
            
            // SEO Components
            'meta_title' => fake()->optional(0.7)->sentence(rand(5, 10)),
            'meta_description' => fake()->optional(0.7)->text(160),
            'meta_keywords' => fake()->optional(0.5)->words(rand(5, 10), true),
            'og_title' => fake()->optional(0.6)->sentence(rand(4, 8)),
            'og_description' => fake()->optional(0.6)->text(150),
            'og_image' => null,
            'twitter_title' => fake()->optional(0.5)->sentence(rand(4, 8)),
            'twitter_description' => fake()->optional(0.5)->text(140),
            'twitter_image' => null,
            
            // Publishing
            'is_published' => fake()->boolean(80), // 80% chance of being published
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-6 months', 'now'),
            'sort_order' => fake()->numberBetween(0, 100),
            
            // Analytics
            'views_count' => fake()->numberBetween(0, 5000),
            'reading_time' => $readingTime,
        ];
    }

    /**
     * Indicate that the blog post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the blog post is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the blog post is featured (high views).
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'views_count' => fake()->numberBetween(1000, 10000),
            'sort_order' => fake()->numberBetween(1, 10),
        ]);
    }

    /**
     * Create a blog post with specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }
}
