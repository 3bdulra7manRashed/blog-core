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
        'viewAllText' => 'المزيد من ' . $categoryOne['category']->name,
        'bgClass'     => 'bg-gray-50',
    ])
@endif

{{-- Category Two Section --}}
@if($categoryTwo['category'] && $categoryTwo['posts']->isNotEmpty())
    @include('landing::front.partials.section-grid', [
        'title'       => $categoryTwo['category']->name,
        'subtitle'    => $categoryTwo['category']->description,
        'items'       => $categoryTwo['posts'],
        'viewAllUrl'  => route('category.show', $categoryTwo['category']->slug),
        'viewAllText' => 'المزيد من ' . $categoryTwo['category']->name,
        
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
