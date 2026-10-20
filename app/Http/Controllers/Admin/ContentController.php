<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\Seo;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $hero     = SiteSetting::getGroup('hero');
        $site     = SiteSetting::getGroup('site');
        $social   = SiteSetting::getGroup('social');
        $audience = SiteSetting::getGroup('audience');
        $socials  = Seo::socials();

        return view('admin.content', compact('hero', 'site', 'social', 'audience', 'socials'));
    }

    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'hero_title_line1'      => 'required|string|max:100',
            'hero_title_line2'      => 'required|string|max:100',
            'hero_title_line3_gold' => 'required|string|max:100',
            'hero_badge_text'       => 'nullable|string|max:60',
            'hero_subtitle'         => 'required|string|max:500',
        ]);

        SiteSetting::setMany($validated, 'hero');

        return back()->with('success_hero', 'Hero section saved successfully.');
    }

    /**
     * Subscriber and view counts for each YouTube channel, entered by hand.
     * The site shows the totals (hero, footer, llms.txt).
     */
    public function updateAudience(Request $request)
    {
        $rules = [];
        foreach (array_keys(Seo::AUDIENCE_CHANNELS) as $key) {
            $rules["audience_{$key}_subscribers"] = 'nullable|integer|min:0|max:1000000000';
            $rules["audience_{$key}_views"]       = 'nullable|integer|min:0|max:100000000000';
        }

        $validated = $request->validate($rules, [
            '*.integer' => 'Enter a whole number without commas or K/M (e.g. 15200).',
        ]);

        SiteSetting::setMany($validated, 'audience');
        SiteSetting::set('audience_updated_at', now()->toDateString(), 'audience');

        return back()->with('success_audience', 'Audience stats saved.');
    }

    public function updateSite(Request $request)
    {
        $validated = $request->validate([
            'site_title'       => 'required|string|max:100',
            'site_tagline'     => 'nullable|string|max:100',
            'site_description' => 'required|string|max:600',
            'contact_email'    => 'required|email|max:150',
            'contact_phone'    => 'required|string|max:30',
        ]);

        SiteSetting::setMany($validated, 'site');

        return back()->with('success_site', 'Site settings saved successfully.');
    }

    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'social_youtube'         => 'nullable|url|max:200',
            'social_youtube_english' => 'nullable|url|max:200',
            'social_spotify'         => 'nullable|url|max:200',
            'social_instagram'       => 'nullable|url|max:200',
            'social_linkedin'        => 'nullable|url|max:200',
        ]);

        SiteSetting::setMany($validated, 'social');

        return back()->with('success_social', 'Social links saved successfully.');
    }
}
