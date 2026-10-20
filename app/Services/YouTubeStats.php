<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Live subscriber / view counts for the show's YouTube channels, via the
 * YouTube Data API v3 (channels.list, 1 quota unit per channel per sync).
 *
 * Channels come from the YouTube URLs in Admin > Content (Kannada + English),
 * results are stored in site_settings so pages never call the API directly.
 */
class YouTubeStats
{
    public const SETTING_KEY = 'youtube_stats';
    public const STALE_AFTER_HOURS = 12;

    private const API_URL = 'https://www.googleapis.com/youtube/v3/channels';

    public static function apiKey(): ?string
    {
        return config('services.youtube.key') ?: null;
    }

    /**
     * Channel handles (e.g. "@BusinessGiseness") parsed from the YouTube URL settings.
     */
    public static function handles(): array
    {
        $urls = [
            SiteSetting::get('social_youtube') ?: 'https://www.youtube.com/@BusinessGiseness',
            SiteSetting::get('social_youtube_english') ?: 'https://www.youtube.com/@BusinessGisenessEnglish',
        ];

        return array_values(array_unique(array_filter(array_map([self::class, 'handleFromUrl'], $urls))));
    }

    public static function handleFromUrl(?string $url): ?string
    {
        if ($url && preg_match('#youtube\.com/(@[\w.\-]+)#i', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * Last stored snapshot, or null if never synced.
     *
     * @return array{channels: array, totals: array{subscribers:int, views:int, videos:int}, synced_at: string}|null
     */
    public static function stored(): ?array
    {
        $raw = SiteSetting::get(self::SETTING_KEY);
        $data = $raw ? json_decode($raw, true) : null;

        return is_array($data) && isset($data['totals']) ? $data : null;
    }

    public static function isStale(): bool
    {
        $data = self::stored();

        return !$data || Carbon::parse($data['synced_at'])->lt(now()->subHours(self::STALE_AFTER_HOURS));
    }

    /**
     * Fetch fresh numbers from the API and store them.
     * Returns the stored snapshot, or null if nothing could be fetched.
     */
    public static function sync(): ?array
    {
        $key = self::apiKey();
        if (!$key) {
            return null;
        }

        $previous = collect(self::stored()['channels'] ?? [])->keyBy('handle');
        $channels = [];

        foreach (self::handles() as $handle) {
            try {
                $response = Http::timeout(8)->retry(1, 500, throw: false)->get(self::API_URL, [
                    'part' => 'snippet,statistics',
                    'forHandle' => $handle,
                    'key' => $key,
                ]);

                $item = $response->successful() ? ($response->json('items.0') ?? null) : null;

                if (!$item) {
                    Log::warning('YouTube stats: no data for channel', ['handle' => $handle, 'status' => $response->status(), 'error' => $response->json('error.message')]);
                    if ($previous->has($handle)) {
                        $channels[] = $previous[$handle]; // keep last known numbers
                    }
                    continue;
                }

                $stats = $item['statistics'] ?? [];
                $channels[] = [
                    'handle' => $handle,
                    'id' => $item['id'] ?? null,
                    'title' => $item['snippet']['title'] ?? $handle,
                    'subscribers' => !empty($stats['hiddenSubscriberCount']) ? null : (int) ($stats['subscriberCount'] ?? 0),
                    'views' => (int) ($stats['viewCount'] ?? 0),
                    'videos' => (int) ($stats['videoCount'] ?? 0),
                ];
            } catch (\Throwable $e) {
                Log::warning('YouTube stats: request failed', ['handle' => $handle, 'error' => $e->getMessage()]);
                if ($previous->has($handle)) {
                    $channels[] = $previous[$handle];
                }
            }
        }

        if (!$channels) {
            return null;
        }

        $data = [
            'channels' => $channels,
            'totals' => [
                'subscribers' => array_sum(array_map(fn ($c) => (int) $c['subscribers'], $channels)),
                'views' => array_sum(array_column($channels, 'views')),
                'videos' => array_sum(array_column($channels, 'videos')),
            ],
            'synced_at' => now()->toIso8601String(),
        ];

        SiteSetting::set(self::SETTING_KEY, json_encode($data), 'youtube');

        return $data;
    }

    /**
     * Refresh after the response is sent when the numbers are stale, so hosts
     * without a cron job still stay current. At most one attempt per hour.
     */
    public static function refreshInBackgroundIfStale(): void
    {
        if (!self::apiKey() || !self::isStale() || !Cache::add('youtube_stats_refresh_attempt', true, now()->addHour())) {
            return;
        }

        \Illuminate\Support\defer(fn () => self::sync());
    }

    /**
     * 15234 → "15.2K", 1234567 → "1.2M", 950 → "950".
     */
    public static function compact(int $number): string
    {
        foreach ([1_000_000_000 => 'B', 1_000_000 => 'M', 1_000 => 'K'] as $size => $suffix) {
            if ($number >= $size) {
                $value = $number / $size;
                $formatted = $value >= 100 ? number_format(floor($value)) : rtrim(rtrim(number_format(floor($value * 10) / 10, 1), '0'), '.');

                return $formatted . $suffix;
            }
        }

        return (string) $number;
    }
}
