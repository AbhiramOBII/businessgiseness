<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Guest;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Services\YouTubeStats;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Serve robots.txt, pointing the Sitemap directive at the domain set in Admin > Settings.
     */
    public function robots(): Response
    {
        $domain = rtrim(SiteSetting::get('general_sitemap_domain') ?: url('/'), '/');

        $privatePaths = ['/admin', '/admin/', '/login', '/password', '/profile', '/dashboard'];
        $rules = array_merge(['Allow: /'], array_map(fn ($path) => "Disallow: {$path}", $privatePaths));

        // AI search and assistant crawlers are welcomed explicitly so the show can be
        // cited in AI answers. A named group replaces "*" for that bot, so it repeats the rules.
        $aiCrawlers = [
            'GPTBot', 'OAI-SearchBot', 'ChatGPT-User',
            'ClaudeBot', 'Claude-SearchBot', 'Claude-User',
            'PerplexityBot', 'Perplexity-User',
            'Google-Extended', 'Applebot-Extended', 'Bingbot', 'CCBot',
        ];

        $lines = array_merge(
            ['User-agent: *'], $rules, [''],
            ['# AI search & assistant crawlers'],
            array_map(fn ($bot) => "User-agent: {$bot}", $aiCrawlers), $rules, [''],
            ["Sitemap: {$domain}/sitemap.xml"],
        );

        return response(implode("\n", $lines) . "\n", 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    /**
     * Serve /llms.txt — a plain-Markdown briefing for AI assistants (llmstxt.org).
     */
    public function llms(): Response
    {
        $socials  = Seo::socials();
        $contact  = Seo::contact();
        $stats    = Seo::stats();
        $episodes = Episode::published()->latest('published_at')->limit(15)->get();
        $posts    = BlogPost::published()->latest('published_at')->limit(8)->get();

        $md = [];
        $md[] = '# ' . Seo::NAME . ' (' . Seo::NAME_KN . ')';
        $md[] = '';
        $md[] = '> ' . Seo::summary();
        $md[] = '';
        $md[] = '## Key facts';
        $md[] = '- Category: ' . Seo::CATEGORY . ' (' . Seo::CATEGORY_KN . ')';
        $md[] = '- Languages: Kannada and English';
        $md[] = '- Based in: Bengaluru, Karnataka, India';
        $md[] = '- Host: ' . Seo::HOST_NAME . ' (' . Seo::HOST_URL . ')';
        $md[] = '- Produced by: ' . Seo::PRODUCER_NAME . ' (' . Seo::PRODUCER_URL . ')';
        $md[] = '- Format: long-form video and audio conversations with founders and entrepreneurs';
        $md[] = '- Published episodes on this site: ' . Episode::published()->count();
        if ($youtube = YouTubeStats::stored()) {
            $asOf = \Illuminate\Support\Carbon::parse($youtube['synced_at'])->format('j F Y');
            $md[] = '- YouTube audience (as of ' . $asOf . '): ' . number_format($youtube['totals']['subscribers']) . ' subscribers and '
                . number_format($youtube['totals']['views']) . ' views across ' . count($youtube['channels']) . ' channels';
            foreach ($youtube['channels'] as $channel) {
                $md[] = '  - ' . $channel['title'] . ' (' . $channel['handle'] . '): '
                    . ($channel['subscribers'] !== null ? number_format($channel['subscribers']) . ' subscribers, ' : '')
                    . number_format($channel['views']) . ' views, ' . $channel['videos'] . ' videos';
            }
        } else {
            foreach ($stats as $stat) {
                // The live episode count above is authoritative; the hero stat is hand-edited
                if (strcasecmp($stat['label'], 'Episodes') !== 0) {
                    $md[] = '- ' . $stat['label'] . ': ' . $stat['value'];
                }
            }
        }
        if ($guests = Seo::notableGuests(8)) {
            $md[] = '- Notable guests: ' . implode(', ', $guests);
        }
        $md[] = '- Guest enquiries: ' . $contact['email'] . ', ' . $contact['phone'];
        $md[] = '';
        $md[] = '## Watch and listen';
        $platformNames = ['youtube' => 'YouTube — Kannada channel', 'youtube_english' => 'YouTube — English channel', 'spotify' => 'Spotify', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'];
        foreach ($socials as $platform => $url) {
            $md[] = '- [' . ($platformNames[$platform] ?? ucfirst($platform)) . '](' . $url . ')';
        }
        $md[] = '';
        $md[] = '## Key pages';
        $md[] = '- [Home](' . route('home') . '): latest episodes and what the show is about';
        $md[] = '- [Kannada Business Podcast](' . route('kannada-business-podcast') . '): why the show exists and who it is for';
        $md[] = '- [About and host](' . route('about-business-giseness-podcast') . '): the story behind the show and host ' . Seo::HOST_NAME;
        $md[] = '- [All episodes](' . route('episodes.index') . ')';
        $md[] = '- [Kannada episodes](' . route('episodes.index', ['language' => 'kn']) . ')';
        $md[] = '- [Guests](' . route('guests.index') . ')';
        $md[] = '- [Blog](' . route('blog.index') . '): business lessons drawn from the conversations';
        $md[] = '';
        $md[] = '## Latest episodes';
        foreach ($episodes as $episode) {
            $language = $episode->language_label ? ' [' . $episode->language_label . ']' : '';
            $md[] = '- [' . $episode->title . '](' . route('episodes.show', $episode) . ')' . $language . ': ' . trim(preg_replace('/\s+/', ' ', strip_tags($episode->short_description)));
        }
        if ($posts->isNotEmpty()) {
            $md[] = '';
            $md[] = '## Latest articles';
            foreach ($posts as $post) {
                $md[] = '- [' . $post->title . '](' . route('blog.show', $post) . '): ' . trim(preg_replace('/\s+/', ' ', strip_tags($post->short_description)));
            }
        }
        $md[] = '';
        $md[] = '## Frequently asked questions';
        foreach (Seo::faqs() as $faq) {
            $md[] = '';
            $md[] = '### ' . $faq['q'];
            $md[] = $faq['a'];
        }

        return response(implode("\n", $md) . "\n", 200)
            ->header('Content-Type', 'text/markdown; charset=utf-8');
    }

    /**
     * Generate the XML sitemap for public pages.
     */
    public function index(Request $request): Response
    {
        $episodes  = Episode::published()->orderByDesc('published_at')->get();
        $guests    = Guest::orderBy('name')->get();
        $blogPosts = BlogPost::published()->orderByDesc('published_at')->get();

        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

        // Listing pages change when their newest content changes
        $latestEpisode = $episodes->max('updated_at')?->tz('UTC')->toAtomString();
        $latestContent = collect([$episodes->max('updated_at'), $blogPosts->max('updated_at')])->filter()->max()?->tz('UTC')->toAtomString();

        $this->addUrl($xml, route('home'), $latestContent, 'daily', '1.0');
        $this->addUrl($xml, route('kannada-business-podcast'), $latestEpisode, 'weekly', '0.9');
        $this->addUrl($xml, route('about-business-giseness-podcast'), null, 'monthly', '0.8');
        $this->addUrl($xml, route('episodes.index'), $latestEpisode, 'daily', '0.9');
        $this->addUrl($xml, route('guests.index'), now()->startOfWeek()->toAtomString(), 'weekly', '0.7');
        $this->addUrl($xml, route('blog.index'), now()->toAtomString(), 'weekly', '0.7');
        $this->addUrl($xml, route('privacy-policy'), '2025-01-01T00:00:00+00:00', 'yearly', '0.3');
        $this->addUrl($xml, route('terms-of-use'), '2025-01-01T00:00:00+00:00', 'yearly', '0.3');
        $this->addUrl($xml, route('disclaimer'), '2025-01-01T00:00:00+00:00', 'yearly', '0.3');

        foreach ($episodes as $episode) {
            $lastMod = ($episode->updated_at ?: $episode->published_at)?->tz('UTC')->toAtomString();
            $this->addUrl($xml, route('episodes.show', $episode), $lastMod, 'weekly', '0.8');
        }

        foreach ($guests as $guest) {
            $lastMod = $guest->updated_at?->tz('UTC')->toAtomString();
            $this->addUrl($xml, route('guests.show', $guest), $lastMod, 'weekly', '0.6');
        }

        foreach ($blogPosts as $post) {
            $lastMod = ($post->updated_at ?: $post->published_at)?->tz('UTC')->toAtomString();
            $this->addUrl($xml, route('blog.show', $post), $lastMod, 'weekly', '0.7');
        }

        $xml->endElement();
        $xml->endDocument();

        return response($xml->outputMemory(), 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    private function addUrl(\XMLWriter $xml, string $loc, ?string $lastmod, string $changefreq, string $priority): void
    {
        $xml->startElement('url');
        $xml->writeElement('loc', $loc);
        if ($lastmod) {
            $xml->writeElement('lastmod', $lastmod);
        }
        $xml->writeElement('changefreq', $changefreq);
        $xml->writeElement('priority', $priority);
        $xml->endElement();
    }
}
