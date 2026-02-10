<?php

namespace Modules\Landing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Support\Landing\LandingDataService;
use App\Support\SEO\SeoManager;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(SeoManager $seoManager, LandingDataService $dataService): View
    {
        $settings = LandingSetting::current();

        // Resolve all sections via service (clean architecture)
        $hero = $dataService->getHeroData($settings);
        $cta = $dataService->getCtaData($settings);
        $thoughts = $dataService->getThoughts($settings);
        $categoryOne = $dataService->getCategorySection($settings->show_category_one, $settings->category_one_id);
        $categoryTwo = $dataService->getCategorySection($settings->show_category_two, $settings->category_two_id);
        $khutab = $dataService->getKhutab($settings);
        $releases = $dataService->getReleases($settings);
        $latestPosts = $dataService->getLatestPosts($settings);

        // Set SEO via SeoManager
        $seoManager->forPage([
            'title' => $hero['title'],
            'description' => $hero['subtitle'],
            'canonical' => url('/'),
            'type' => 'website',
            'image' => $hero['image']
                ? (str_starts_with($hero['image'], 'http')
                    ? $hero['image']
                    : asset('storage/' . $hero['image']))
                : null,
        ]);

        return view('landing::front.index', [
            'settings' => $settings,
            'hero' => $hero,
            'cta' => $cta,
            'thoughts' => $thoughts,
            'categoryOne' => $categoryOne,
            'categoryTwo' => $categoryTwo,
            'khutab' => $khutab,
            'releases' => $releases,
            'latestPosts' => $latestPosts,
        ]);
    }
}

