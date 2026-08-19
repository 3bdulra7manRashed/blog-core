@extends('layouts.blog')

@section('content')

{{-- Hero Section --}}
@include('landing::front.partials.hero')

{{-- Thoughts Section (Stories-style) --}}
@if($thoughts->isNotEmpty())
    @include('landing::front.partials.thoughts')
@endif

{{-- Category One Section --}}
@if($categoryOne['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $categoryOne['category'] ? $categoryOne['category']->name : 'أحدث المقالات',
        'subtitle'    => $categoryOne['category'] ? $categoryOne['category']->description : null,
        'items'       => $categoryOne['posts'],
        'viewAllUrl'  => $categoryOne['category'] ? route('category.show', $categoryOne['category']->slug) : route('posts.index'),
        'viewAllText' => $categoryOne['category'] ? ('المزيد من ' . $categoryOne['category']->name) : 'جميع المقالات',
        'bgClass'     => 'bg-gray-50',
    ])
@endif

{{-- Category Two Section --}}
@if($categoryTwo['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $categoryTwo['category'] ? $categoryTwo['category']->name : 'أحدث المقالات',
        'subtitle'    => $categoryTwo['category'] ? $categoryTwo['category']->description : null,
        'items'       => $categoryTwo['posts'],
        'viewAllUrl'  => $categoryTwo['category'] ? route('category.show', $categoryTwo['category']->slug) : route('posts.index'),
        'viewAllText' => $categoryTwo['category'] ? ('المزيد من ' . $categoryTwo['category']->name) : 'جميع المقالات',
    ])
@endif

{{-- Khutab Section --}}
@if($khutab['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $khutab['category'] ? $khutab['category']->name : 'الخطب',
        'subtitle'    => null,
        'items'       => $khutab['posts'],
        'viewAllUrl'  => Route::has('khutab.index') ? route('khutab.index') : null,
        'viewAllText' => 'جميع الخطب',
        'bgClass'     => 'bg-gray-50',
        'itemRoute'   => 'khutab.show',
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
        'subtitle'    => 'تصفّح أحدث ما كُتب من مقالات ومواضيع',
        'items'       => $latestPosts,
        'viewAllUrl'  => route('home'),
        'viewAllText' => 'جميع المقالات',
        'bgClass'     => 'bg-gray-50',
    ])
@endif

@endsection
