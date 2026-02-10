<?php

namespace Modules\Landing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\SEO\SeoManager;
use Illuminate\View\View;
use Modules\Landing\Models\LandingSetting;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(SeoManager $seoManager): View
    {
        // Get landing settings
        $settings = LandingSetting::getInstance();

        // Get latest 6 published posts for quotes section
        $latestPosts = Post::with(['author', 'categories'])
            ->posts()
            ->published()
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Set SEO via SeoManager
        $seoManager->forPage([
            'title' => $settings->hero_title ?? config('app.name'),
            'description' => $settings->hero_subtitle ?? config('branding.site_description', 'مرحباً بكم في موقعنا'),
            'canonical' => url('/'),
            'type' => 'website',
            'image' => $settings->hero_image 
                ? (str_starts_with($settings->hero_image, 'http') 
                    ? $settings->hero_image 
                    : asset('storage/' . $settings->hero_image))
                : null,
        ]);

        return view('landing::front.index', compact('settings', 'latestPosts'));
    }
}
