<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Guest;
use App\Models\BlogPost;
use App\Models\SiteSetting;
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

        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Disallow: /login',
            'Disallow: /password',
            'Disallow: /profile',
            'Disallow: /dashboard',
            '',
            "Sitemap: {$domain}/sitemap.xml",
        ];

        return response(implode("\n", $lines) . "\n", 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
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

        $this->addUrl($xml, route('home'), now()->toAtomString(), 'daily', '1.0');
        $this->addUrl($xml, route('about-business-giseness-podcast'), now()->startOfMonth()->toAtomString(), 'monthly', '0.8');
        $this->addUrl($xml, route('episodes.index'), now()->toAtomString(), 'daily', '0.9');
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
