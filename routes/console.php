<?php

use App\Services\YouTubeStats;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('youtube:sync-stats', function () {
    if (!YouTubeStats::apiKey()) {
        $this->error('YOUTUBE_API_KEY is not set in .env — see README "Live YouTube stats".');
        return 1;
    }

    $this->info('Fetching stats for: ' . implode(', ', YouTubeStats::handles()));
    $data = YouTubeStats::sync();

    if (!$data) {
        $this->error('Could not fetch any channel stats. Check storage/logs/laravel.log.');
        return 1;
    }

    $this->table(
        ['Channel', 'Subscribers', 'Views', 'Videos'],
        array_map(fn ($c) => [$c['title'] . ' (' . $c['handle'] . ')', $c['subscribers'] ?? 'hidden', number_format($c['views']), $c['videos']], $data['channels'])
    );
    $this->info(sprintf('Total: %s subscribers, %s views', YouTubeStats::compact($data['totals']['subscribers']), YouTubeStats::compact($data['totals']['views'])));

    return 0;
})->purpose('Refresh live YouTube subscriber and view counts for the site');

Schedule::command('youtube:sync-stats')->everySixHours()->withoutOverlapping();
