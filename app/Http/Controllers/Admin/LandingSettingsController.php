<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLandingSettingsRequest;
use App\Models\Category;
use App\Models\LandingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LandingSettingsController extends Controller
{
    /**
     * Show the landing settings form.
     */
    public function edit(): View
    {
        $settings = LandingSetting::current();
        $categories = $this->getCategoryOptions();

        return view('theme::admin.landing.settings', compact('settings', 'categories'));
    }

    /**
     * Update the landing settings.
     */
    public function update(UpdateLandingSettingsRequest $request): RedirectResponse
    {
        $settings = LandingSetting::current();
        $data = $request->validated();

        if ($request->hasFile('hero_mobile_image')) {
            if ($settings->hero_mobile_image && Storage::disk('public')->exists($settings->hero_mobile_image)) {
                Storage::disk('public')->delete($settings->hero_mobile_image);
            }
            $path = $request->file('hero_mobile_image')->store('landing', 'public');
            $data['hero_mobile_image'] = $path;
        } elseif ($request->filled('hero_mobile_image_url')) {
            $data['hero_mobile_image'] = $request->input('hero_mobile_image_url');
        }
        unset($data['hero_mobile_image_url']);
        // Strip hero_bg fields — stored separately in the generic settings table
        unset($data['hero_bg_type'], $data['hero_bg_color_1'], $data['hero_bg_color_2'], $data['hero_bg_angle'], $data['hero_bg_preset']);

        // Handle boolean checkboxes (not sent when unchecked)
        $booleanFields = [
            'show_quotes_section',
            'show_thoughts',
            'show_category_one',
            'show_category_two',
            'show_khutab',
            'show_releases',
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field);
        }

        $categoryFields = ['category_one_id', 'category_two_id', 'khutab_category_id'];
        foreach ($categoryFields as $field) {
            $data[$field] = $request->filled($field) ? (int) $request->input($field) : null;
        }

        $settings->update($data);

        // ── Hero Background Settings (theme-isolated, stored in generic settings table) ──
        $activeTheme = theme_name();
        $bgFields = [
            'hero_bg_type'    => $request->input('hero_bg_type', 'solid'),
            'hero_bg_color_1' => $request->input('hero_bg_color_1'),
            'hero_bg_color_2' => $request->input('hero_bg_color_2'),
            'hero_bg_angle'   => $request->input('hero_bg_angle', 135),
            'hero_bg_preset'  => $request->input('hero_bg_preset'),
        ];

        foreach ($bgFields as $shortKey => $value) {
            $fullKey = "theme_{$activeTheme}_{$shortKey}";
            if ($value !== null) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $fullKey],
                    ['value' => $value]
                );
            }
            Cache::forget("setting.{$fullKey}");
        }

        return redirect()
            ->route('admin.settings.landing.edit')
            ->with('success', 'تم حفظ إعدادات الصفحة الرئيسية بنجاح');
    }

    /**
     * Get all categories for select dropdowns (id + name only).
     */
    private function getCategoryOptions(): \Illuminate\Support\Collection
    {
        return Category::select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}

