<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
            'site_logo_url' => 'nullable|string',
            'site_logo_base64' => 'nullable|string',
        ]);

        // Update site name
        if ($request->has('site_name')) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_name'],
                ['value' => $request->site_name]
            );
            Cache::forget('setting.site_name');
        }

        // Update site logo — Base64 cropped image takes priority over raw URL
        $logoValue = null;

        if ($request->filled('site_logo_base64')) {
            // Decode base64 data and save as file
            $data = $request->site_logo_base64;

            // Strip the "data:image/png;base64," prefix
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
                $extension = $matches[1];
                $data = substr($data, strpos($data, ',') + 1);
            } else {
                $extension = 'png';
            }

            $decoded = base64_decode($data);
            $filename = 'logo_' . time() . '.' . $extension;

            Storage::disk('public')->put('settings/' . $filename, $decoded);

            $logoValue = url('storage/settings/' . $filename);

        } elseif ($request->filled('site_logo_url')) {
            $logoValue = $request->site_logo_url;
        }

        if ($logoValue !== null) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $logoValue]
            );
            Cache::forget('setting.site_logo');
        }

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
