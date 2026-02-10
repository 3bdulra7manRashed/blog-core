<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLandingSettingsRequest;
use App\Models\Category;
use App\Models\LandingSetting;
use Illuminate\Http\RedirectResponse;
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

        $settings->update($data);

        return redirect()
            ->route('admin.landing.settings.edit')
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

