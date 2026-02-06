{{--
    SEO Meta Tags Component
    
    Usage:
    1. In controller: app(SeoManager::class)->forModel($post)
    2. In blade: <x-seo />
    
    OR for static pages:
    <x-seo
        :title="'My Page Title'"
        :description="'My page description'"
        :image="asset('images/og.jpg')"
    />
--}}

@php
    use App\Support\SEO\SeoManager;
    
    /** @var SeoManager $seoManager */
    $seoManager = app(SeoManager::class);
    
    // If component props are passed, use them to set page data
    if (isset($title) || isset($description)) {
        $seoManager->forPage([
            'title' => $title ?? null,
            'description' => $description ?? null,
            'canonicalUrl' => $canonicalUrl ?? url()->current(),
            'image' => $image ?? null,
            'type' => $type ?? 'website',
            'robots' => $robots ?? 'index, follow',
        ]);
    }
@endphp

{{-- Render SEO tags if basic SEO is enabled --}}
@if($seoManager->isBasicSeoEnabled())
{!! $seoManager->render() !!}
@endif
