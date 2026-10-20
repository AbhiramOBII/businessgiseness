<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\Seo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AudienceStatsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['is_admin' => true]);
    }

    public function test_compact_number_formatting(): void
    {
        $this->assertSame('950', Seo::compact(950));
        $this->assertSame('5.3K', Seo::compact(5330));
        $this->assertSame('15K', Seo::compact(15020));
        $this->assertSame('327K', Seo::compact(327890));
        $this->assertSame('1.2M', Seo::compact(1_250_000));
    }

    public function test_admin_saves_per_channel_numbers_and_site_shows_totals(): void
    {
        Episode::create(['title' => 'Ep', 'slug' => 'ep', 'short_description' => 's', 'long_description' => 'l', 'is_published' => true, 'published_at' => now()->subDay()]);
        SiteSetting::setMany(['hero_stat_2_value' => '1K', 'hero_stat_2_label' => 'Subscribers'], 'hero');

        // Own page, linked from the sidebar and from Content Management
        $this->actingAs($this->admin())->get('/admin/youtube-stats')->assertOk()
            ->assertSee('Audience Stats')->assertSee('Kannada channel')->assertSee('English channel')
            ->assertSee('Save Audience Stats')->assertDontSee('Sync now');
        $this->actingAs($this->admin())->get('/admin/content')->assertOk()
            ->assertSee('YouTube Stats')->assertSee(route('admin.audience'), false);
        $this->actingAs($this->admin())->get('/admin/dashboard')->assertOk()
            ->assertSee(route('admin.audience'), false);

        $this->actingAs($this->admin())->post('/admin/content/audience', [
            'audience_kn_subscribers' => '10000',
            'audience_kn_views' => '250000',
            'audience_en_subscribers' => '5330',
            'audience_en_views' => '80000',
        ])->assertRedirect()->assertSessionHas('success_audience');

        $audience = Seo::audience();
        $this->assertSame(15330, $audience['subscribers']);
        $this->assertSame(330000, $audience['views']);
        $this->assertSame(now()->toDateString(), $audience['updated_at']);

        // Homepage hero + footer show the totals and the real episode count, not the old free-form value
        $this->get('/')->assertOk()
            ->assertSee('15.3K')->assertSee('330K')
            ->assertDontSee('>1K<', false);
        $this->assertSame([['value' => '1', 'label' => 'Episodes'], ['value' => '15.3K', 'label' => 'Subscribers'], ['value' => '330K', 'label' => 'Views']], Seo::stats());

        $this->get('/llms.txt')->assertOk()
            ->assertSee('15,330 subscribers and 330,000 views across 2 channels', false)
            ->assertSee('English channel (@BusinessGisenessEnglish): 5,330 subscribers', false);
    }

    public function test_numbers_must_be_whole_numbers(): void
    {
        $this->actingAs($this->admin())->post('/admin/content/audience', [
            'audience_kn_subscribers' => '15.2K',
        ])->assertSessionHasErrors('audience_kn_subscribers');

        $this->assertNull(Seo::audience());
    }

    public function test_falls_back_to_old_hero_stats_until_audience_is_entered(): void
    {
        SiteSetting::setMany(['hero_stat_2_value' => '12.3K', 'hero_stat_2_label' => 'Subscribers'], 'hero');

        $this->assertNull(Seo::audience());
        $this->get('/')->assertOk()->assertSee('12.3K');
    }
}
