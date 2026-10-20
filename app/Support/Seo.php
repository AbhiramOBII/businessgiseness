<?php

namespace App\Support;

use App\Models\Episode;
use App\Models\Guest;
use App\Models\SiteSetting;
use App\Services\YouTubeStats;
use Illuminate\Support\Facades\Cache;

/**
 * Single source of truth for how Business Giseness describes itself to search
 * engines, answer engines and AI assistants. Keep the facts here consistent:
 * the same name, positioning and links should appear everywhere.
 */
class Seo
{
    public const NAME = 'Business Giseness';
    public const NAME_KN = 'ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್';
    public const CATEGORY = 'Kannada Business Podcast';
    public const CATEGORY_KN = 'ಕನ್ನಡ ಬಿಸಿನೆಸ್ ಪಾಡ್‌ಕ್ಯಾಸ್ಟ್';
    public const TAGLINE_KN = 'ಕನ್ನಡದ ಉದ್ಯಮಿಗಳ ನಿಜ ಕಥೆಗಳು';

    public const HOST_NAME = 'Abhiram Chandramohan';
    public const HOST_URL = 'https://www.abhiramchandramohan.com/';
    public const PRODUCER_NAME = 'Obii Kriationz Web LLP';
    public const PRODUCER_URL = 'https://www.obiikriationz.com';

    public const DEFAULT_TITLE = 'Business Giseness — Kannada Business Podcast | Founder Stories from Karnataka';

    /**
     * One-sentence answer to "What is Business Giseness?" — reused in meta
     * descriptions, schema, FAQs and llms.txt so every surface agrees.
     */
    public static function summary(): string
    {
        return 'Business Giseness (' . self::NAME_KN . ') is a Kannada business podcast from Bengaluru, hosted by '
            . self::HOST_NAME . ', featuring honest, long-form conversations with founders and entrepreneurs from Karnataka in Kannada and English.';
    }

    public static function metaDescription(): string
    {
        return 'Business Giseness is a Kannada business podcast from Bengaluru. Real founder stories from Karnataka in Kannada & English — startups, family businesses, struggle, growth and lessons. Watch on YouTube & Spotify.';
    }

    public static function logoUrl(): string
    {
        return asset('images/business-giseness-logo.webp');
    }

    public static function hostImageUrl(): string
    {
        return asset('images/abhiram-chandramohan-founder-obii-kriationz.webp');
    }

    public static function contact(): array
    {
        return [
            'email' => SiteSetting::get('contact_email') ?: 'info@obiikriationz.com',
            'phone' => SiteSetting::get('contact_phone') ?: '+91 9964 102 103',
        ];
    }

    /**
     * Official profiles, keyed by platform. Empty settings are dropped.
     */
    public static function socials(): array
    {
        return array_filter([
            'youtube' => SiteSetting::get('social_youtube') ?: 'https://www.youtube.com/@BusinessGiseness',
            'youtube_english' => SiteSetting::get('social_youtube_english') ?: 'https://www.youtube.com/@BusinessGisenessEnglish',
            'spotify' => SiteSetting::get('social_spotify') ?: 'https://creators.spotify.com/pod/profile/business-giseness/',
            'instagram' => SiteSetting::get('social_instagram') ?: 'https://www.instagram.com/businessgisenessenglish/',
            'linkedin' => SiteSetting::get('social_linkedin') ?: 'https://www.linkedin.com/company/business-giseness/',
        ]);
    }

    /**
     * Audience stats for the hero, footer and llms.txt.
     *
     * Uses live YouTube numbers (both channels combined) plus the real published
     * episode count when available; otherwise falls back to the hand-entered
     * hero stats in Admin > Content. Each item: value, label, and live flag.
     */
    public static function stats(): array
    {
        YouTubeStats::refreshInBackgroundIfStale();

        if ($youtube = YouTubeStats::stored()) {
            return Cache::remember('seo_live_stats_' . md5($youtube['synced_at']), 600, fn () => [
                ['value' => (string) Episode::published()->count(), 'label' => 'Episodes', 'live' => true],
                ['value' => YouTubeStats::compact($youtube['totals']['subscribers']), 'label' => 'Subscribers', 'live' => true],
                ['value' => YouTubeStats::compact($youtube['totals']['views']), 'label' => 'Views', 'live' => true],
            ]);
        }

        $stats = [];
        foreach ([1, 2, 3] as $i) {
            $value = SiteSetting::get("hero_stat_{$i}_value");
            $label = SiteSetting::get("hero_stat_{$i}_label");
            if ($value && $label) {
                $stats[] = ['value' => $value, 'label' => $label, 'live' => false];
            }
        }

        return $stats;
    }

    /**
     * Names of a few featured guests, for answers like "who has been on the show?"
     */
    public static function notableGuests(int $limit = 6): array
    {
        return Cache::remember("seo_notable_guests_{$limit}", 600, function () use ($limit) {
            return Guest::orderByDesc('is_featured')->ordered()->limit($limit)->pluck('name')->all();
        });
    }

    public static function latestEpisodes(int $limit = 4)
    {
        return Cache::remember("seo_latest_episodes_{$limit}", 600, function () use ($limit) {
            return Episode::published()->latest('published_at')->limit($limit)->get();
        });
    }

    /**
     * Visible FAQ content (also emitted as FAQPage schema). Answers are written
     * to stand on their own when quoted by search or AI answer engines.
     */
    public static function faqs(): array
    {
        $contact = self::contact();
        $socials = self::socials();
        $guests = self::notableGuests(5);

        $faqs = [
            [
                'q' => 'What is Business Giseness?',
                'a' => self::summary() . ' Each episode goes behind the success story — the struggles, decisions and lessons of people who are still building.',
            ],
            [
                'q' => 'Is there a business podcast in Kannada?',
                'a' => 'Yes. Business Giseness (' . self::NAME_KN . ') is a Kannada business podcast made in Bengaluru. Most episodes are conversations in Kannada with Karnataka\'s founders, and selected episodes are recorded in English so the stories reach a wider audience.',
            ],
            [
                'q' => 'Who hosts Business Giseness?',
                'a' => 'Business Giseness is hosted by ' . self::HOST_NAME . ', a Bengaluru-based entrepreneur and founder of Obii Kriationz. The show is produced by ' . self::PRODUCER_NAME . '.',
            ],
            [
                'q' => 'What topics does Business Giseness cover?',
                'a' => 'Founder journeys, startups, family businesses, marketing and digital growth, real estate, food and FMCG brands, education, the business of Kannada cinema, leadership and personal resilience — always told through the real experience of the guest.',
            ],
        ];

        if ($guests) {
            $faqs[] = [
                'q' => 'Who has appeared on Business Giseness?',
                'a' => 'Guests include ' . self::listSentence($guests) . ', along with many other founders, creators and business leaders from Karnataka.',
            ];
        }

        $platforms = [];
        if ($handle = YouTubeStats::handleFromUrl($socials['youtube'] ?? null)) {
            $platforms[] = 'YouTube (Kannada channel ' . $handle . ')';
        }
        if ($handle = YouTubeStats::handleFromUrl($socials['youtube_english'] ?? null)) {
            $platforms[] = 'YouTube (English channel ' . $handle . ')';
        }
        if (isset($socials['spotify'])) {
            $platforms[] = 'Spotify';
        }
        $faqs[] = [
            'q' => 'Where can I watch or listen to Business Giseness?',
            'a' => 'Full episodes are on ' . self::listSentence($platforms ?: ['YouTube']) . ', and every episode is also listed with show notes on businessgiseness.com/episodes.',
        ];

        $faqs[] = [
            'q' => 'How can I be a guest on Business Giseness?',
            'a' => 'Founders and entrepreneurs with a real story to tell can write to ' . $contact['email'] . ' or call ' . $contact['phone'] . '. The show looks for builders who are still in the journey, not only those who have already "made it".',
        ];

        return $faqs;
    }

    public static function faqSchema(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ], $faqs),
        ];
    }

    public static function id(string $fragment): string
    {
        return url('/') . '/#' . $fragment;
    }

    /**
     * Sitewide entity graph: Organization, WebSite, PodcastSeries and host.
     * Page-level schema links to these via their @id.
     */
    public static function graph(): array
    {
        $socials = array_values(self::socials());
        $contact = self::contact();

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => self::id('organization'),
                    'name' => self::NAME,
                    'alternateName' => [self::NAME_KN, 'Business Giseness Podcast'],
                    'url' => url('/'),
                    'logo' => ['@type' => 'ImageObject', 'url' => self::logoUrl()],
                    'description' => self::summary(),
                    'founder' => ['@id' => self::id('host')],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Bengaluru',
                        'addressRegion' => 'Karnataka',
                        'addressCountry' => 'IN',
                    ],
                    'areaServed' => ['@type' => 'State', 'name' => 'Karnataka'],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'guest bookings and partnerships',
                        'email' => $contact['email'],
                        'telephone' => $contact['phone'],
                        'availableLanguage' => ['Kannada', 'English'],
                    ],
                    'sameAs' => $socials,
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => self::id('website'),
                    'name' => self::NAME,
                    'alternateName' => self::NAME_KN,
                    'url' => url('/'),
                    'inLanguage' => 'en-IN',
                    'publisher' => ['@id' => self::id('organization')],
                    'about' => ['@id' => self::id('podcast')],
                ],
                [
                    '@type' => 'PodcastSeries',
                    '@id' => self::id('podcast'),
                    'name' => self::NAME,
                    'alternateName' => self::NAME_KN,
                    'description' => self::summary(),
                    'url' => url('/'),
                    'image' => self::logoUrl(),
                    'inLanguage' => ['kn', 'en'],
                    'genre' => ['Business', 'Entrepreneurship', 'Kannada Podcast'],
                    'keywords' => 'Kannada business podcast, Kannada podcast, Karnataka founders, Bengaluru entrepreneurs, startup stories in Kannada, ' . self::CATEGORY_KN,
                    'locationCreated' => ['@type' => 'Place', 'name' => 'Bengaluru, Karnataka, India'],
                    'author' => ['@id' => self::id('host')],
                    'publisher' => ['@id' => self::id('organization')],
                    'producer' => ['@type' => 'Organization', 'name' => self::PRODUCER_NAME, 'url' => self::PRODUCER_URL],
                    'sameAs' => $socials,
                ],
                [
                    '@type' => 'Person',
                    '@id' => self::id('host'),
                    'name' => self::HOST_NAME,
                    'jobTitle' => 'Host, Business Giseness',
                    'url' => route('about-business-giseness-podcast'),
                    'image' => self::hostImageUrl(),
                    'worksFor' => ['@id' => self::id('organization')],
                    'homeLocation' => ['@type' => 'Place', 'name' => 'Bengaluru, Karnataka, India'],
                    'knowsLanguage' => ['Kannada', 'English'],
                    'sameAs' => [self::HOST_URL, 'https://www.linkedin.com/in/abhiram-chandramohan/'],
                ],
            ],
        ];
    }

    /**
     * JSON for a <script type="application/ld+json"> block. Escapes "</" so
     * content can never close the script tag.
     */
    public static function json(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    }

    private static function listSentence(array $items): string
    {
        if (count($items) <= 1) {
            return implode('', $items);
        }

        $last = array_pop($items);

        return implode(', ', $items) . ' and ' . $last;
    }
}
