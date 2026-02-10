@extends('layouts.blog')

@section('content')
{{-- Hero Section --}}
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    {{-- Background Image or Gradient --}}
    @if($settings->hero_image)
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ str_starts_with($settings->hero_image, 'http') ? $settings->hero_image : asset('storage/' . $settings->hero_image) }}" 
                alt="{{ $settings->hero_title ?? config('app.name') }}"
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60"></div>
        </div>
    @else
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-brand-primary via-brand-secondary to-brand-accent"></div>
    @endif

    {{-- Content --}}
    <div class="relative z-10 container mx-auto px-4 text-center max-w-4xl py-16">
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6 leading-tight drop-shadow-lg">
            {{ $settings->hero_title ?? config('app.name') }}
        </h1>
        
        @if($settings->hero_subtitle)
            <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed drop-shadow">
                {{ $settings->hero_subtitle }}
            </p>
        @endif

        {{-- CTA Buttons --}}
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="px-8 py-3 bg-white text-brand-primary font-bold rounded-full hover:bg-gray-100 transition-colors shadow-lg">
                تصفح المقالات
            </a>
            @if(feature('contact') && Route::has('contact'))
                <a href="{{ route('contact') }}" class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-brand-primary transition-all">
                    تواصل معي
                </a>
            @endif
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>
{{-- Thoughts Section (Stories-style) --}}
@if($thoughts->isNotEmpty())
<section class="py-10">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex gap-6 overflow-x-auto pb-4">
            @foreach($thoughts as $thought)
                <div class="flex flex-col items-center w-24 shrink-0">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-brand-primary">
                        <img src="{{ $thought->image ? asset('storage/'.$thought->image) : asset('images/default-avatar.png') }}"
                             alt="{{ $thought->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs mt-2 text-center line-clamp-2">
                        {{ $thought->title }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Latest Posts Section --}}
@if($settings->show_quotes_section && $latestPosts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-6xl">
        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-brand-primary mb-4">أحدث المقالات</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">اطلع على أحدث ما كتبته من مقالات ومواضيع</p>
        </div>

        {{-- Posts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestPosts as $post)
                <article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                    {{-- Featured Image --}}
                    @if($post->featured_image_url)
                        <a href="{{ route('post.show', $post->slug) }}" class="block aspect-video overflow-hidden">
                            <img 
                                src="{{ $post->featured_image_url }}" 
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        </a>
                    @else
                        <a href="{{ route('post.show', $post->slug) }}" class="block aspect-video bg-gradient-to-br from-brand-secondary/20 to-brand-accent/20 flex items-center justify-center">
                            <svg class="w-12 h-12 text-brand-accent/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>
                    @endif

                    {{-- Content --}}
                    <div class="p-5 text-right">
                        {{-- Categories --}}
                        @if($post->categories->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($post->categories->take(2) as $category)
                                    <span class="text-xs font-medium text-brand-accent bg-brand-accent/10 px-2 py-1 rounded">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <h3 class="text-xl font-serif font-bold text-brand-primary mb-3 line-clamp-2 group-hover:text-brand-accent transition-colors">
                            <a href="{{ route('post.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h3>

                        @if($post->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $post->excerpt }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500 pt-3 border-t border-gray-100">
                            <span>{{ $post->published_at->format('Y/m/d') }}</span>
                            <a href="{{ route('post.show', $post->slug) }}" class="text-brand-accent hover:text-brand-primary font-medium transition-colors">
                                اقرأ المزيد ←
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="text-center mt-12">
            <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-3 bg-brand-accent text-white font-bold rounded-full hover:bg-amber-600 transition-colors shadow-lg">
                عرض جميع المقالات
                <svg class="w-5 h-5 mr-2 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Newsletter Section (reuses existing footer behavior) --}}
@module('Newsletter')
<section class="py-16 bg-gradient-to-br from-brand-accent/5 to-brand-accent/10">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <x-newsletter-form />
    </div>
</section>
@endmodule
@endsection
