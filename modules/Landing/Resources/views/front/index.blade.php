@extends('layouts.blog')

@section('content')

{{-- Hero Section --}}
@include('landing::front.partials.hero')

{{-- Thoughts Section (Stories-style) --}}
@if($thoughts->isNotEmpty())
    @include('landing::front.partials.thoughts')
@endif

{{-- Category One Section --}}
@if($categoryOne['category'] && $categoryOne['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $categoryOne['category']->name,
        'subtitle'    => $categoryOne['category']->description,
        'items'       => $categoryOne['posts'],
        'viewAllUrl'  => route('category.show', $categoryOne['category']->slug),
        'viewAllText' => 'عرض المزيد من ' . $categoryOne['category']->name,
        'bgClass'     => 'bg-gray-50',
    ])
@endif

{{-- Khutab Section --}}
@if($khutab['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $khutab['category'] ? $khutab['category']->name : 'الخطب',
        'subtitle'    => null,
        'items'       => $khutab['posts'],
        'viewAllUrl'  => Route::has('khutab.index') ? route('khutab.index') : null,
        'viewAllText' => 'عرض جميع الخطب',
    ])
@endif

{{-- Category Two Section --}}
@if($categoryTwo['category'] && $categoryTwo['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $categoryTwo['category']->name,
        'subtitle'    => $categoryTwo['category']->description,
        'items'       => $categoryTwo['posts'],
        'viewAllUrl'  => route('category.show', $categoryTwo['category']->slug),
        'viewAllText' => 'عرض المزيد من ' . $categoryTwo['category']->name,
        'bgClass'     => 'bg-gray-50',
    ])
@endif

{{-- Releases Section --}}
@if($releases->isNotEmpty())
    @include('landing::front.partials.releases')
@endif

{{-- Latest Posts Section --}}
@if($latestPosts->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => 'أحدث المقالات',
        'subtitle'    => 'اطلع على أحدث ما كتبته من مقالات ومواضيع',
        'items'       => $latestPosts,
        'viewAllUrl'  => route('home'),
        'viewAllText' => 'عرض جميع المقالات',
        'bgClass'     => 'bg-gray-50',
    ])
@endif

{{-- Newsletter Section --}}
@module('Newsletter')
<section class="py-16 bg-gradient-to-br from-brand-accent/5 to-brand-accent/10">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <x-newsletter-form />
    </div>
</section>
@endmodule

@endsection
