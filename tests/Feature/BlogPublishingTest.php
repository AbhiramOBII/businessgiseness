<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPublishingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['is_admin' => true]);
    }

    private function formData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Startup Economics',
            'slug' => 'startup-economics',
            'category' => 'Startups',
            'short_description' => 'The hidden numbers.',
            'description' => '<p>Body</p>',
            'is_published' => '1',
            'published_at' => '',
        ], $overrides);
    }

    public function test_app_runs_on_india_time(): void
    {
        $this->assertSame('Asia/Kolkata', config('app.timezone'));
    }

    public function test_post_published_with_the_current_india_time_is_visible_immediately(): void
    {
        // What the admin's datetime-local field sends when they pick "now" in India
        $nowInIndia = now('Asia/Kolkata')->format('Y-m-d\TH:i');

        $this->actingAs($this->admin())
            ->post('/admin/blog', $this->formData(['published_at' => $nowInIndia]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Blog post created and live on the blog.');

        $this->get('/blog')->assertSee('Startup Economics');
        $this->get('/blog/startup-economics')->assertOk();
    }

    public function test_future_date_is_scheduled_and_admin_is_told(): void
    {
        $admin = $this->admin();
        $later = now()->addDay()->format('Y-m-d\TH:i');

        $this->actingAs($admin)->post('/admin/blog', $this->formData(['published_at' => $later]))
            ->assertSessionHas('success', fn ($message) => str_contains($message, "It's scheduled"));

        $post = BlogPost::firstOrFail();
        $this->assertTrue($post->isScheduled());
        $this->assertSame(0, BlogPost::published()->count()); // hidden from visitors (admins can still preview)
        $this->get('/blog')->assertDontSee('Startup Economics');

        $this->actingAs($admin)->get('/admin/blog')->assertSee('Scheduled')->assertSee('Goes live');
        $this->actingAs($admin)->get('/admin/blog/startup-economics')->assertSee('Scheduled · goes live', false);
    }

    public function test_unticking_published_saves_as_draft_instead_of_erroring(): void
    {
        $post = BlogPost::factory()->create(['slug' => 'startup-economics', 'is_published' => true, 'published_at' => now()->subDay()]);
        $data = $this->formData(['published_at' => $post->published_at->format('Y-m-d\TH:i')]);
        unset($data['is_published']); // unticked checkbox sends nothing

        $this->actingAs($this->admin())->put('/admin/blog/startup-economics', $data)
            ->assertRedirect()
            ->assertSessionHas('success', fn ($message) => str_contains($message, 'draft'));

        $this->assertFalse($post->fresh()->is_published);
        $this->assertSame(0, BlogPost::published()->count()); // hidden from visitors (admins can still preview)
        $this->get('/blog')->assertDontSee('Startup Economics');
    }

    public function test_clearing_the_date_keeps_a_published_post_visible(): void
    {
        $originalDate = now()->subWeek()->startOfMinute();
        $post = BlogPost::factory()->create(['slug' => 'startup-economics', 'is_published' => true, 'published_at' => $originalDate]);

        $this->actingAs($this->admin())->put('/admin/blog/startup-economics', $this->formData(['published_at' => '']))
            ->assertRedirect();

        $this->assertTrue($post->fresh()->published_at->equalTo($originalDate));
        $this->get('/blog')->assertSee('Startup Economics');
    }
}
