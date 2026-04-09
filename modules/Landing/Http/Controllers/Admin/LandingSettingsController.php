<?php

namespace Modules\Landing\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Landing\Http\Requests\UpdateLandingSettingsRequest;
use Modules\Landing\Models\LandingSetting;

class LandingSettingsController extends Controller
{
    /**
     * Show the landing settings form.
     */
    public function edit(): View
    {
        $settings = LandingSetting::getInstance();

        return view('landing::admin.settings', compact('settings'));
    }

    /**
     * Update the landing settings.
     */
    public function update(UpdateLandingSettingsRequest $request): RedirectResponse
    {
        $settings = LandingSetting::getInstance();
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('hero_image')) {
            // Delete old image if exists
            if ($settings->hero_image && Storage::disk('public')->exists($settings->hero_image)) {
                Storage::disk('public')->delete($settings->hero_image);
            }

            $path = $request->file('hero_image')->store('landing', 'public');
            $data['hero_image'] = $path;
        } elseif ($request->filled('hero_image_url')) {
            // Use external URL
            $data['hero_image'] = $request->input('hero_image_url');
        }

        if ($request->hasFile('hero_mobile_image')) {
            // Delete old image if exists
            if ($settings->hero_mobile_image && Storage::disk('public')->exists($settings->hero_mobile_image)) {
                Storage::disk('public')->delete($settings->hero_mobile_image);
            }

            $path = $request->file('hero_mobile_image')->store('landing', 'public');
            $data['hero_mobile_image'] = $path;
        } elseif ($request->filled('hero_mobile_image_url')) {
            // Use external URL
            $data['hero_mobile_image'] = $request->input('hero_mobile_image_url');
        }

        // Remove URLs from data (not db columns)
        unset($data['hero_image_url']);
        unset($data['hero_mobile_image_url']);

        // Handle checkbox (not sent when unchecked)
        $data['show_quotes_section'] = $request->has('show_quotes_section');

        $settings->update($data);

        return redirect()
            ->route('admin.settings.landing.edit')
            ->with('success', 'تم حفظ إعدادات الصفحة الرئيسية بنجاح');
    }
}
