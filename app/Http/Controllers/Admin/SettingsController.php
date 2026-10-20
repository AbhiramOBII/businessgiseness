<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $seo     = SiteSetting::getGroup('seo');
        $general = SiteSetting::getGroup('general');

        return view('admin.settings', compact('seo', 'general'));
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'seo_site_title'           => 'nullable|string|max:100',
            'seo_meta_description'     => 'nullable|string|max:300',
            'seo_default_og_image_url' => 'nullable|url|max:500',
            'seo_ga4_id'               => ['nullable', 'string', 'max:30', 'regex:/^G-[A-Z0-9]+$/'],
            'seo_gsc_verification'     => 'nullable|string|max:200',
            'seo_robots'               => ['nullable', Rule::in(['index, follow', 'noindex, follow', 'index, nofollow', 'noindex, nofollow'])],
        ]);

        SiteSetting::setMany($validated, 'seo');

        return back()->with('success_seo', 'SEO settings saved.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Password updated successfully.');
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'general_sitemap_domain'   => 'nullable|url|max:255',
        ]);

        SiteSetting::setMany($validated, 'general');

        return back()->with('success_general', 'General settings saved.');
    }
}
