<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => $this->faker->sentence(10),
            'description' => $this->faker->paragraphs(3, true),
            'website' => $this->faker->optional(0.7)->url(),
            'twitter' => $this->faker->optional(0.6)->userName(),
            'linkedin' => $this->faker->optional(0.5)->userName(),
            'instagram' => $this->faker->optional(0.4)->userName(),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the guest is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'sort_order' => $this->faker->numberBetween(0, 10),
        ]);
    }

    /**
     * Indicate that the guest has social media presence.
     */
    public function withSocialMedia(): static
    {
        return $this->state(fn (array $attributes) => [
            'website' => $this->faker->url(),
            'twitter' => $this->faker->userName(),
            'linkedin' => $this->faker->userName(),
            'instagram' => $this->faker->userName(),
        ]);
    }

    /**
     * Create a guest with minimal social media.
     */
    public function minimal(): static
    {
        return $this->state(fn (array $attributes) => [
            'website' => null,
            'twitter' => null,
            'linkedin' => null,
            'instagram' => null,
        ]);
    }
}
