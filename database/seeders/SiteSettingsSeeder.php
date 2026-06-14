<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // ── Hero ──────────────────────────────────────────
            ['key' => 'hero_title_line1',     'value' => 'Founder Stories',              'group' => 'hero'],
            ['key' => 'hero_title_line2',     'value' => 'That Deserve to',              'group' => 'hero'],
            ['key' => 'hero_title_line3_gold','value' => 'Be Heard',                     'group' => 'hero'],
            ['key' => 'hero_badge_text',      'value' => 'Now Streaming',                'group' => 'hero'],
            ['key' => 'hero_subtitle',        'value' => 'Business Giseness is a bilingual podcast show that brings real founder stories to life, in English and Kannada, through honest conversations about business, struggle, growth and the journey behind building something meaningful.', 'group' => 'hero'],
            ['key' => 'hero_stat_1_value',    'value' => '23',                           'group' => 'hero'],
            ['key' => 'hero_stat_1_label',    'value' => 'Episodes',                     'group' => 'hero'],
            ['key' => 'hero_stat_2_value',    'value' => '15.2K',                        'group' => 'hero'],
            ['key' => 'hero_stat_2_label',    'value' => 'Subscribers',                  'group' => 'hero'],
            ['key' => 'hero_stat_3_value',    'value' => '309K',                         'group' => 'hero'],
            ['key' => 'hero_stat_3_label',    'value' => 'Views',                        'group' => 'hero'],

            // ── Site Settings ─────────────────────────────────
            ['key' => 'site_title',           'value' => 'Business Giseness',            'group' => 'site'],
            ['key' => 'site_tagline',         'value' => 'Founder-First Stories',        'group' => 'site'],
            ['key' => 'site_description',     'value' => 'Business Giseness is a bilingual podcast show featuring real founder stories in English and Kannada. Honest conversations about entrepreneurship, business building, struggle and growth — from Bengaluru and beyond.', 'group' => 'site'],
            ['key' => 'contact_email',        'value' => 'info@obiikriationz.com',       'group' => 'site'],
            ['key' => 'contact_phone',        'value' => '+91 9964 102 103',             'group' => 'site'],

            // ── Social Links ──────────────────────────────────
            ['key' => 'social_youtube',       'value' => 'https://www.youtube.com/@BusinessGiseness',                      'group' => 'social'],
            ['key' => 'social_spotify',       'value' => 'https://creators.spotify.com/pod/profile/business-giseness/',    'group' => 'social'],
            ['key' => 'social_instagram',     'value' => 'https://www.instagram.com/businessgisenessenglish/',             'group' => 'social'],
            ['key' => 'social_linkedin',      'value' => 'https://www.linkedin.com/company/business-giseness/',            'group' => 'social'],
        ];

        foreach ($defaults as $row) {
            SiteSetting::firstOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'], 'group' => $row['group']]
            );
        }
    }
}
