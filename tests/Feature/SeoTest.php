<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Episode;
use App\Models\Guest;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function episode(string $title, ?string $language = null, array $attributes = []): Episode
    {
        return Episode::create(array_merge([
            'title' => $title,
            'slug' => Str::slug($title),
            'language' => $language,
            'short_description' => 'Short about ' . $title,
            'long_description' => 'Long',
            'youtube_link' => 'https://www.youtube.com/watch?v=abcdefghijk&t=10s',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    /** Decode every JSON-LD block on a page, failing on invalid JSON. */
    private function jsonLd(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        return array_map(function ($json) {
            $data = json_decode($json, true);
            $this->assertNotNull($data, 'Invalid JSON-LD: ' . json_last_error_msg());
            return $data;
        }, $matches[1]);
    }

    private function findType(array $blocks, string $type): ?array
    {
        foreach ($blocks as $block) {
            foreach ($block['@graph'] ?? [$block] as $node) {
                if (($node['@type'] ?? null) === $type) {
                    return $node;
                }
            }
        }

        return null;
    }

    public function test_home_positions_show_as_kannada_business_podcast(): void
    {
        $html = $this->get('/')->assertOk()
            ->assertSee('<title>Business Giseness — Kannada Business Podcast', false)
            ->assertSee('ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್')
            ->assertSee('Is there a business podcast in Kannada?')
            ->getContent();

        $blocks = $this->jsonLd($html);
        $podcast = $this->findType($blocks, 'PodcastSeries');
        $this->assertSame(['kn', 'en'], $podcast['inLanguage']);
        $this->assertContains('ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್', (array) $podcast['alternateName']);
        $this->assertNotNull($this->findType($blocks, 'Organization'));
        $this->assertNotNull($this->findType($blocks, 'FAQPage'));
        $this->assertNull($this->findType($blocks, 'SearchAction'));
        $this->assertSame(1, substr_count($html, '<main'));
    }

    public function test_every_public_page_has_one_og_title_and_valid_schema(): void
    {
        $episode = $this->episode('Founder Story', 'kn');
        $guest = Guest::factory()->create(['name' => 'Asha Rao', 'slug' => 'asha-rao']);
        $guest->episodes()->attach($episode->id, ['is_host' => false, 'sort_order' => 1]);
        $post = BlogPost::factory()->create(['slug' => 'lesson', 'is_published' => true, 'published_at' => now()->subDay()]);

        foreach (['/', '/kannada-business-podcast', '/about-business-giseness-podcast', '/episodes', '/episodes?language=kn',
                  '/episodes/founder-story', '/guests', '/guests/asha-rao', '/blog', '/blog/lesson'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertSame(1, substr_count($html, 'property="og:title"'), "Duplicate og:title on {$url}");
            $this->assertSame(1, substr_count($html, 'name="robots"'), "Duplicate robots on {$url}");
            $this->assertNotNull($this->findType($this->jsonLd($html), 'PodcastSeries'), "Missing entity graph on {$url}");
        }
    }

    public function test_episode_schema_has_language_video_and_guests(): void
    {
        $episode = $this->episode('Kannada Founder', 'kn');
        $guest = Guest::factory()->create(['name' => 'Ravi Kumar', 'slug' => 'ravi-kumar']);
        $guest->episodes()->attach($episode->id, ['is_host' => false, 'sort_order' => 1]);

        $html = $this->get('/episodes/kannada-founder')->assertOk()
            ->assertSee('Featuring')
            ->assertSee('Ravi Kumar')
            ->getContent();

        $schema = $this->findType($this->jsonLd($html), 'PodcastEpisode');
        $this->assertSame('kn', $schema['inLanguage']);
        $this->assertSame('https://www.youtube.com/embed/abcdefghijk', $schema['associatedMedia']['embedUrl']);
        $this->assertSame('https://i.ytimg.com/vi/abcdefghijk/hqdefault.jpg', $schema['associatedMedia']['thumbnailUrl']);
        $this->assertSame('Ravi Kumar', $schema['actor'][0]['name']);
        $this->assertSame(\App\Support\Seo::id('podcast'), $schema['partOfSeries']['@id']);
    }

    public function test_episodes_can_be_filtered_by_language(): void
    {
        $this->episode('Kannada Only', 'kn');
        $this->episode('English Only', 'en');
        $this->episode('Bilingual One', 'both');
        $this->episode('Untagged One');

        // Only look inside <main>: the footer always lists the latest episodes of any language
        $main = fn ($response) => preg_match('#<main>(.*)</main>#s', $response->getContent(), $m) ? $m[1] : '';

        $kn = $this->get('/episodes?language=kn')->assertOk()
            ->assertSee('<link rel="canonical" href="' . url('/episodes?language=kn') . '">', false);
        $this->assertStringContainsString('Kannada Only', $main($kn));
        $this->assertStringContainsString('Bilingual One', $main($kn));
        $this->assertStringNotContainsString('English Only', $main($kn));
        $this->assertStringNotContainsString('Untagged One', $main($kn));

        $en = $this->get('/episodes?language=en')->assertOk();
        $this->assertStringContainsString('English Only', $main($en));
        $this->assertStringContainsString('Bilingual One', $main($en));
        $this->assertStringNotContainsString('Kannada Only', $main($en));

        // Unknown values fall back to all episodes
        $all = $this->get('/episodes?language=xx')->assertOk();
        $this->assertStringContainsString('Untagged One', $main($all));
        $this->assertStringContainsString('Kannada Only', $main($all));
    }

    public function test_kannada_landing_page(): void
    {
        $this->episode('Kannada Episode', 'kn');
        $this->episode('Other Episode', 'en');

        $html = $this->get('/kannada-business-podcast')->assertOk()
            ->assertSee('The Kannada business podcast for', false)
            ->assertSee('Kannada Episode')
            ->assertSee('Other Episode')
            ->getContent();

        $this->assertNotNull($this->findType($this->jsonLd($html), 'FAQPage'));
        $this->get('/sitemap.xml')->assertOk()->assertSee(route('kannada-business-podcast'), false);
    }

    public function test_llms_txt_summarises_the_show(): void
    {
        $this->episode('Latest Kannada Talk', 'kn');
        SiteSetting::setMany(['hero_stat_1_value' => '99', 'hero_stat_1_label' => 'Episodes', 'hero_stat_2_value' => '15K', 'hero_stat_2_label' => 'Subscribers'], 'hero');

        $this->get('/llms.txt')->assertOk()
            ->assertHeader('Content-Type', 'text/markdown; charset=utf-8')
            ->assertSee('# Business Giseness (ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್)', false)
            ->assertSee('Kannada business podcast from Bengaluru')
            ->assertSee('Latest Kannada Talk')
            ->assertSee('[Kannada]', false)
            ->assertSee('- Subscribers: 15K', false)
            ->assertDontSee('- Episodes: 99', false)
            ->assertSee('[YouTube — Kannada channel](', false)
            ->assertSee('### Is there a business podcast in Kannada?', false);
    }

    public function test_robots_txt_welcomes_ai_crawlers_but_keeps_admin_private(): void
    {
        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        foreach (['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Google-Extended'] as $bot) {
            $this->assertStringContainsString("User-agent: {$bot}", $robots);
        }
        // Each group must repeat the private-path rules
        $this->assertSame(2, substr_count($robots, 'Disallow: /admin/'));
    }

    public function test_admin_can_set_episode_language(): void
    {
        $admin = \App\Models\User::factory()->create()->forceFill(['is_admin' => true]);
        $episode = $this->episode('Needs Tag');

        $this->actingAs($admin)->get('/admin/episodes/needs-tag/edit')->assertOk()->assertSee('Episode Language');

        $this->actingAs($admin)->put('/admin/episodes/needs-tag', [
            'title' => 'Needs Tag',
            'slug' => 'needs-tag',
            'language' => 'kn',
            'short_description' => 'Short',
            'long_description' => 'Long',
            'is_published' => '1',
        ])->assertRedirect();

        $this->assertSame('kn', $episode->fresh()->language);

        $this->actingAs($admin)->put('/admin/episodes/needs-tag', [
            'title' => 'Needs Tag', 'slug' => 'needs-tag', 'language' => 'fr',
            'short_description' => 'Short', 'long_description' => 'Long', 'is_published' => '1',
        ])->assertSessionHasErrors('language');
    }
}
