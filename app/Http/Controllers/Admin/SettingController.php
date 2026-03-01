<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display the general settings view.
     */
    public function index()
    {
        return view('admin.settings.general');
    }

    /**
     * Update the general settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|max:5120', // max 5MB
        ]);

        // Update site name
        if ($request->has('site_name')) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_name'],
                ['value' => $request->site_name]
            );
            Cache::forget('setting.site_name');
        }

        // Update site logo
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $path]
            );
            Cache::forget('setting.site_logo');
        }

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
