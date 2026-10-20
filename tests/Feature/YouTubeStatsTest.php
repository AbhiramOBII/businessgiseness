<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use App\Services\YouTubeStats;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YouTubeStatsTest extends TestCase
{
    use RefreshDatabase;

    private array $apiData = [];

    /** Point the fake YouTube API at new data (one fake, so later calls can change it). */
    private function fakeApi(array $byHandle): void
    {
        $alreadyFaked = $this->apiData !== [];
        $this->apiData = $byHandle;

        if ($alreadyFaked) {
            return;
        }

        Http::fake(function (Request $request) {
            parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);
            $stats = $this->apiData[$query['forHandle']] ?? null;

            if ($stats === 'error') {
                return Http::response(['error' => ['message' => 'quota exceeded']], 403);
            }

            return Http::response(['items' => $stats ? [[
                'id' => 'UC' . md5($query['forHandle']),
                'snippet' => ['title' => 'Channel ' . $query['forHandle']],
                'statistics' => $stats,
            ]] : []]);
        });
    }

    public function test_compact_number_formatting(): void
    {
        $this->assertSame('950', YouTubeStats::compact(950));
        $this->assertSame('5.3K', YouTubeStats::compact(5330));
        $this->assertSame('15K', YouTubeStats::compact(15020));
        $this->assertSame('327K', YouTubeStats::compact(327890));
        $this->assertSame('1.2M', YouTubeStats::compact(1_250_000));
    }

    public function test_handles_come_from_youtube_url_settings(): void
    {
        $this->assertSame(['@BusinessGiseness', '@BusinessGisenessEnglish'], YouTubeStats::handles());

        SiteSetting::set('social_youtube_english', 'https://www.youtube.com/@OtherChannel/videos', 'social');
        $this->assertSame(['@BusinessGiseness', '@OtherChannel'], YouTubeStats::handles());
    }

    public function test_sync_combines_both_channels_and_site_shows_live_numbers(): void
    {
        config(['services.youtube.key' => 'test-key']);
        $this->fakeApi([
            '@BusinessGiseness' => ['subscriberCount' => '10000', 'viewCount' => '250000', 'videoCount' => '40'],
            '@BusinessGisenessEnglish' => ['subscriberCount' => '5330', 'viewCount' => '80000', 'videoCount' => '24'],
        ]);
        SiteSetting::setMany(['hero_stat_2_value' => '1K', 'hero_stat_2_label' => 'Subscribers'], 'hero');

        $data = YouTubeStats::sync();

        $this->assertSame(15330, $data['totals']['subscribers']);
        $this->assertSame(330000, $data['totals']['views']);
        $this->assertCount(2, $data['channels']);
        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'key=test-key') && str_contains($r->url(), 'part=snippet%2Cstatistics'));

        // Hero and footer use the live totals, not the hand-entered value
        $this->get('/')->assertOk()->assertSee('15.3K')->assertSee('330K')->assertDontSee('>1K<', false);

        $this->get('/llms.txt')->assertOk()
            ->assertSee('15,330 subscribers and 330,000 views across 2 channels', false)
            ->assertSee('@BusinessGisenessEnglish');
    }

    public function test_failed_channel_keeps_last_known_numbers(): void
    {
        config(['services.youtube.key' => 'test-key']);
        $this->fakeApi([
            '@BusinessGiseness' => ['subscriberCount' => '10000', 'viewCount' => '250000', 'videoCount' => '40'],
            '@BusinessGisenessEnglish' => ['subscriberCount' => '5000', 'viewCount' => '80000', 'videoCount' => '24'],
        ]);
        YouTubeStats::sync();

        $this->fakeApi([
            '@BusinessGiseness' => ['subscriberCount' => '11000', 'viewCount' => '260000', 'videoCount' => '41'],
            '@BusinessGisenessEnglish' => 'error',
        ]);
        $data = YouTubeStats::sync();

        $this->assertSame(16000, $data['totals']['subscribers']);
    }

    public function test_without_api_key_site_falls_back_to_admin_values(): void
    {
        config(['services.youtube.key' => null]);
        Http::fake();
        SiteSetting::setMany(['hero_stat_2_value' => '12.3K', 'hero_stat_2_label' => 'Subscribers'], 'hero');

        $this->assertNull(YouTubeStats::sync());
        $this->get('/')->assertOk()->assertSee('12.3K');
        Http::assertNothingSent();
    }

    public function test_stale_stats_refresh_after_the_response(): void
    {
        config(['services.youtube.key' => 'test-key']);
        $this->fakeApi([
            '@BusinessGiseness' => ['subscriberCount' => '100', 'viewCount' => '1000', 'videoCount' => '1'],
            '@BusinessGisenessEnglish' => ['subscriberCount' => '50', 'viewCount' => '500', 'videoCount' => '1'],
        ]);

        $this->assertTrue(YouTubeStats::isStale());
        $this->get('/')->assertOk(); // deferred callbacks run once the response is sent

        $this->assertSame(150, YouTubeStats::stored()['totals']['subscribers']);
        $this->assertFalse(YouTubeStats::isStale());
    }

    public function test_sync_command_and_admin_button(): void
    {
        config(['services.youtube.key' => 'test-key']);
        $this->fakeApi([
            '@BusinessGiseness' => ['subscriberCount' => '200', 'viewCount' => '3000', 'videoCount' => '2'],
            '@BusinessGisenessEnglish' => ['subscriberCount' => '100', 'viewCount' => '1000', 'videoCount' => '1'],
        ]);

        $this->artisan('youtube:sync-stats')->assertExitCode(0);
        $this->assertSame(300, YouTubeStats::stored()['totals']['subscribers']);

        $admin = User::factory()->create()->forceFill(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/content')->assertOk()->assertSee('Live YouTube Stats')->assertSee('Sync now');
        $this->actingAs($admin)->post('/admin/content/youtube-sync')->assertRedirect()->assertSessionHas('success_youtube');
    }

    public function test_sync_command_explains_missing_key(): void
    {
        config(['services.youtube.key' => null]);
        $this->artisan('youtube:sync-stats')->expectsOutputToContain('YOUTUBE_API_KEY')->assertExitCode(1);
    }
}
