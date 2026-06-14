<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $hero   = SiteSetting::getGroup('hero');
        $site   = SiteSetting::getGroup('site');
        $social = SiteSetting::getGroup('social');

        return view('admin.content', compact('hero', 'site', 'social'));
    }

    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'hero_title_line1'      => 'required|string|max:100',
            'hero_title_line2'      => 'required|string|max:100',
            'hero_title_line3_gold' => 'required|string|max:100',
            'hero_badge_text'       => 'nullable|string|max:60',
            'hero_subtitle'         => 'required|string|max:500',
            'hero_stat_1_value'     => 'nullable|string|max:20',
            'hero_stat_1_label'     => 'nullable|string|max:40',
            'hero_stat_2_value'     => 'nullable|string|max:20',
            'hero_stat_2_label'     => 'nullable|string|max:40',
            'hero_stat_3_value'     => 'nullable|string|max:20',
            'hero_stat_3_label'     => 'nullable|string|max:40',
        ]);

        SiteSetting::setMany($validated, 'hero');

        return back()->with('success_hero', 'Hero section saved successfully.');
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
            'social_youtube'   => 'nullable|url|max:200',
            'social_spotify'   => 'nullable|url|max:200',
            'social_instagram' => 'nullable|url|max:200',
            'social_linkedin'  => 'nullable|url|max:200',
        ]);

        SiteSetting::setMany($validated, 'social');

        return back()->with('success_social', 'Social links saved successfully.');
    }
}
