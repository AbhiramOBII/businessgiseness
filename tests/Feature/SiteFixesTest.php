<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Episode;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteFixesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['is_admin' => true]);
    }

    private function episode(string $title, bool $published): Episode
    {
        return Episode::create([
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'short_description' => 'Short',
            'long_description' => 'Long',
            'is_published' => $published,
            'published_at' => $published ? now()->subDay() : null,
        ]);
    }

    public function test_drafts_are_hidden_from_home_and_blog(): void
    {
        BlogPost::factory()->create(['title' => 'Live Post', 'slug' => 'live-post', 'is_published' => true, 'published_at' => now()->subDay()]);
        BlogPost::factory()->create(['title' => 'Secret Draft', 'slug' => 'secret-draft', 'is_published' => false, 'published_at' => null]);
        BlogPost::factory()->create(['title' => 'Future Post', 'slug' => 'future-post', 'is_published' => true, 'published_at' => now()->addWeek()]);
        $this->episode('Live Episode', true);
        $this->episode('Draft Episode', false);

        $this->get('/')->assertOk()
            ->assertSee('Live Post')->assertDontSee('Secret Draft')->assertDontSee('Future Post')
            ->assertSee('Live Episode')->assertDontSee('Draft Episode');

        $this->get('/blog')->assertOk()
            ->assertSee('Live Post')->assertDontSee('Secret Draft')->assertDontSee('Future Post');

        $this->get('/blog/live-post')->assertOk();
        $this->get('/blog/secret-draft')->assertNotFound();
        $this->get('/blog/future-post')->assertNotFound();
    }

    public function test_admin_can_preview_draft_without_counting_a_view(): void
    {
        $draft = BlogPost::factory()->create(['slug' => 'secret-draft', 'is_published' => false, 'published_at' => null, 'views_count' => 0]);

        $this->actingAs($this->admin())->get('/blog/secret-draft')->assertOk();

        $this->assertSame(0, $draft->fresh()->views_count);
    }

    public function test_seo_settings_are_used_as_layout_defaults(): void
    {
        SiteSetting::setMany([
            'seo_site_title' => 'Custom Default Title',
            'seo_meta_description' => 'Custom default description',
            'seo_robots' => 'noindex, follow',
            'seo_default_og_image_url' => 'https://cdn.example.com/og.jpg',
        ], 'seo');

        $this->get('/guests')->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee('<meta property="og:image" content="https://cdn.example.com/og.jpg">', false);

        // Legal pages now actually render their own meta description
        $this->get('/privacy-policy')->assertOk()
            ->assertSee('Privacy Policy for Business Giseness podcast', false);
    }

    public function test_robots_txt_uses_sitemap_domain_setting(): void
    {
        SiteSetting::set('general_sitemap_domain', 'https://businessgiseness.com/', 'general');

        $this->get('/robots.txt')->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=utf-8')
            ->assertSee('Sitemap: https://businessgiseness.com/sitemap.xml', false)
            ->assertSee('Disallow: /admin', false);
    }

    public function test_admin_list_sorting_rejects_unknown_columns(): void
    {
        $admin = $this->admin();
        BlogPost::factory()->count(2)->create();

        $this->actingAs($admin)->get('/admin/blog?sort=password&order=sideways')->assertOk();
        $this->actingAs($admin)->get('/admin/guests?sort=nope&direction=x')->assertOk();
        $this->actingAs($admin)->get('/admin/newsletter-subscriptions?sort=ip_address;drop&order=x')->assertOk();
    }

    public function test_is_admin_cannot_be_mass_assigned(): void
    {
        $user = User::create(['name' => 'X', 'email' => 'x@example.com', 'password' => 'secret123', 'is_admin' => true]);

        $this->assertFalse((bool) $user->fresh()->is_admin);
    }

    public function test_create_admin_command_grants_admin_access(): void
    {
        $this->artisan('admin:create-user', [
            '--name' => 'New Admin',
            '--email' => 'new-admin@example.com',
            '--password' => 'Sup3rSecret!',
        ])->assertExitCode(0);

        $user = User::where('email', 'new-admin@example.com')->first();
        $this->assertTrue($user->is_admin);
        $this->assertNotNull($user->email_verified_at);

        $this->actingAs($user)->get('/admin/dashboard')->assertOk();
    }

    public function test_debug_route_is_removed(): void
    {
        $this->get('/test-guest')->assertNotFound();
    }
}
