@extends('layouts.blog')

{{-- ============================================================ --}}
{{-- 📌 PAGE META SECTIONS                                        --}}
{{-- ============================================================ --}}
@section('title', $post->title)

@section('description'){{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 155) }}@endsection

@section('keywords')خطب, مواعظ, {{ config('branding.default_keywords') }}{{ $post->tags->count() > 0 ? ', ' . $post->tags->pluck('name')->implode(', ') : '' }}@endsection


{{-- ============================================================ --}}
{{-- 🔥 WHATSAPP/FACEBOOK/TWITTER SEO                             --}}
{{-- ============================================================ --}}
@push('meta')
@if($post->published_at)
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
@endif

@if($post->updated_at)
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
@endif

<meta property="article:author" content="{{ $post->publishing_identity->name }}">

@if($post->categories->count() > 0)
@foreach($post->categories as $category)
<meta property="article:section" content="{{ $category->name }}">
@endforeach
@endif

@if($post->tags->count() > 0)
@foreach($post->tags as $tag)
<meta property="article:tag" content="{{ $tag->name }}">
@endforeach
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{{ config('branding.social.twitter_handle') }}">
<meta name="twitter:creator" content="{{ config('branding.social.twitter_handle') }}">
<meta name="twitter:title" content="{{ $post->title }} | {{ config('branding.site_name') }}">
<meta name="twitter:description" content="{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}">
<meta name="twitter:image" content="{{ $post->featured_image_url ?? asset(config('branding.default_og_image')) }}">
@endpush


{{-- ============================================================ --}}
{{-- 🧠 ADVANCED JSON-LD SCHEMA                                   --}}
{{-- ============================================================ --}}
@section('schema')
@php
    $socialLinks = array_filter([
        config('branding.social.twitter'),
        config('branding.social.linkedin'),
    ]);

    $personSchema = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "@id" => url('/') . "/#person",
        "name" => config('branding.author.name'),
        "alternateName" => config('branding.author.name_en'),
        "jobTitle" => config('branding.author.title'),
        "description" => config('branding.author.bio'),
        "url" => url('/'),
        "image" => asset(config('branding.default_og_image')),
        "sameAs" => array_values($socialLinks),
    ];

    $articleSchema = [
        "@context" => "https://schema.org",
        "@type" => "Article",
        "headline" => $post->title,
        "description" => $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160),
        "datePublished" => $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String(),
        "dateModified" => $post->updated_at->toIso8601String(),
        "inLanguage" => "ar",
        "author" => [
            "@id" => url('/') . "/#person"
        ],
        "publisher" => [
            "@type" => "Person",
            "@id" => url('/') . "/#person",
            "name" => config('branding.author.name')
        ],
        "mainEntityOfPage" => [
            "@type" => "WebPage",
            "@id" => url()->current()
        ]
    ];

    if ($post->featured_image_url) {
        $articleSchema['image'] = [
            "@type" => "ImageObject",
            "url" => $post->featured_image_url,
            "width" => 1200,
            "height" => 630
        ];
    }

    if ($post->tags->count() > 0) {
        $articleSchema['keywords'] = $post->tags->pluck('name')->implode(', ') . ', ' . config('branding.default_keywords');
    }

    if ($post->categories->count() > 0) {
        $articleSchema['articleSection'] = $post->categories->pluck('name')->first();
    }

    $breadcrumbSchema = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "الرئيسية",
                "item" => url('/')
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => "الخطب",
                "item" => route('khutab.index')
            ],
            [
                "@type" => "ListItem",
                "position" => 3,
                "name" => $post->title,
                "item" => url()->current()
            ]
        ]
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($personSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection


{{-- ============================================================ --}}
{{-- 🎨 CUSTOM STYLES                                             --}}
{{-- ============================================================ --}}
@push('styles')
<style>
    .post-title {
        line-height: 1.6 !important;
    }
    .post-title span {
        line-height: inherit !important;
        display: inline;
    }
</style>
@endpush


{{-- ============================================================ --}}
{{-- 📄 MAIN CONTENT SECTION                                      --}}
{{-- ============================================================ --}}
@push('progress-bar')
<div id="reading-progress-bar" class="w-full h-1 bg-[#14B8A6] transition-all duration-150"
    style="transform: scaleX(0); transform-origin: right; will-change: transform; backface-visibility: hidden;"></div>
@endpush

@section('content')

<article>
    <!-- Post Header -->
    <div class="container mx-auto px-4 pt-8 pb-6 max-w-5xl">
        <header class="mb-8 text-center max-w-4xl mx-auto">
            <h1 class="post-title text-4xl md:text-4xl font-serif font-bold text-brand-accent mb-4">
                {{ $post->title }}
            </h1>

            <div class="flex items-center justify-center gap-3 text-sm text-gray-500">
                @if($post->categories->count() > 0)
                    @foreach($post->categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="text-gray-500 font-medium hover:text-brand-accent hover:underline transition-colors">
                            {{ $category->name }}
                        </a>
                        @if(!$loop->last)
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                        @endif
                    @endforeach
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                @endif
                <span>{{ $post->published_at->format('Y/m/d') }}</span>
            </div>
        </header>

        @if($post->featured_image_url)
            <div class="mb-12">
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full aspect-video md:aspect-auto md:h-[500px] object-cover rounded-lg shadow-sm">
            </div>
        @endif

        <!-- Post Content -->
        <div class="prose prose-lg max-w-none prose-headings:text-brand-accent prose-headings:font-bold prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-blue-600 prose-img:rounded-xl prose-li:marker:text-brand-accent text-right">
            {!! $post->content !!}
        </div>

        <!-- Tags -->
        @if($post->tags->count() > 0)
            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center space-x-2 space-x-reverse">
                <span class="text-sm font-bold text-brand-primary">الوسوم:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('tag.show', $tag->slug) }}" class="text-sm text-gray-500 hover:text-brand-accent transition-colors">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Engagement Section -->
        <div class="max-w-3xl mx-auto mt-16 mb-12">
            
            <!-- Like Button -->
            <div class="flex justify-center mb-10">
                <button id="like-button" data-post-id="{{ $post->id }}" class="group relative flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.16)] transition-all duration-300 hover:scale-105 active:scale-95">
                    <svg id="heart-filled" class="w-8 h-8 text-red-500 fill-current transition-all duration-300 group-hover:scale-110 hidden" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <svg id="heart-outline" class="w-8 h-8 text-gray-400 transition-all duration-300 group-hover:scale-110 group-hover:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span id="likes-count" class="absolute -top-1 -right-1 w-8 h-8 bg-red-50 rounded-full flex items-center justify-center text-red-600 text-xs font-bold border-2 border-white shadow-sm transition-all duration-300">
                        {{ $post->likes_count > 0 ? ($post->likes_count > 99 ? '99+' : $post->likes_count) : '0' }}
                    </span>
                </button>
            </div>

            <!-- Share Buttons -->
            <div class="mb-10 border-t border-gray-100 pt-8">
                @include('partials.share-buttons', ['post' => $post, 'route' => 'khutab.show', 'hideReadMore' => true])
            </div>

            <!-- Author Bio Card -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex flex-col sm:flex-row-reverse items-center sm:items-start gap-4">
                    <div class="flex-shrink-0">
                        <img src="{{ $post->publishing_identity->profile_photo_url }}" alt="{{ $post->publishing_identity->name }}" class="w-20 h-20 rounded-full object-cover shadow-md">
                    </div>
                    <div class="flex-1 text-center sm:text-right">
                        <h3 class="text-xl font-serif font-bold text-brand-primary mb-2">{{ $post->publishing_identity->name }}</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $post->publishing_identity->short_bio }}
                        </p>
                    </div>
                </div>
            </div>
            
            @module('Newsletter')
            <div class="mt-12 pt-12 border-t border-gray-100">
                <div class="bg-gradient-to-br from-brand-accent/5 to-brand-accent/10 rounded-2xl p-8 shadow-sm border border-brand-accent/20">
                    <x-newsletter-form />
                </div>
            </div>
            @endmodule
        </div>
    </div>

    <!-- Post Navigation -->
    <div class="border-t border-gray-100 py-12 bg-white">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                
                <div class="relative group text-right">
                    @if($nextPost)
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex-shrink-0 hidden sm:block">
                                <a href="{{ route('khutab.show', $nextPost->slug) }}">
                                    @if($nextPost->featured_image_url)
                                        <img src="{{ $nextPost->featured_image_url }}" alt="{{ $nextPost->title }}" class="w-20 h-20 object-cover rounded-full opacity-80 group-hover:opacity-100 transition-opacity">
                                    @else
                                        <div class="w-20 h-20 rounded-full bg-gray-200"></div>
                                    @endif
                                </a>
                            </div>
                            <div>
                                <span class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">الخطبة التالية</span>
                                <a href="{{ route('khutab.show', $nextPost->slug) }}" class="block text-lg font-serif font-bold text-brand-primary group-hover:text-brand-accent transition-colors">
                                    {{ $nextPost->title }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="relative group text-left flex justify-end">
                    @if($previousPost)
                        <div class="flex items-center space-x-4 flex-row-reverse">
                            <div class="flex-shrink-0 hidden sm:block">
                                <a href="{{ route('khutab.show', $previousPost->slug) }}">
                                    @if($previousPost->featured_image_url)
                                        <img src="{{ $previousPost->featured_image_url }}" alt="{{ $previousPost->title }}" class="w-20 h-20 object-cover rounded-full opacity-80 group-hover:opacity-100 transition-opacity">
                                    @else
                                        <div class="w-20 h-20 rounded-full bg-gray-200"></div>
                                    @endif
                                </a>
                            </div>
                            <div>
                                <span class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">الخطبة السابقة</span>
                                <a href="{{ route('khutab.show', $previousPost->slug) }}" class="block text-lg font-serif font-bold text-brand-primary group-hover:text-brand-accent transition-colors">
                                    {{ $previousPost->title }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
        <div class="py-16 bg-gray-50 border-t border-gray-100">
            <div class="container mx-auto px-4 max-w-6xl">
                <h2 class="text-2xl font-serif font-bold mb-8 text-brand-primary text-right">خطب ذات صلة</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $relatedPost)
                        <article class="group text-right">
                            @if($relatedPost->featured_image_url)
                                <a href="{{ route('khutab.show', $relatedPost->slug) }}" class="block mb-4 overflow-hidden rounded-lg">
                                    <img src="{{ $relatedPost->featured_image_url }}" alt="{{ $relatedPost->title }}" class="w-full aspect-video md:aspect-auto md:h-56 object-cover transform group-hover:scale-105 transition-transform duration-500">
                                </a>
                            @endif
                            <div class="mt-4">
                                <h3 class="text-xl font-serif font-bold mb-2 leading-tight">
                                    <a href="{{ route('khutab.show', $relatedPost->slug) }}" class="text-brand-primary hover:text-brand-accent transition-colors">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">{{ $relatedPost->published_at->format('Y/m/d') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</article>
@endsection


{{-- ============================================================ --}}
{{-- 📜 JAVASCRIPT - Reading Progress & Like Button               --}}
{{-- ============================================================ --}}
@push('scripts')
<script>
    // Reading Progress Bar (GPU-Optimized)
    (function() {
        const progressBar = document.getElementById('reading-progress-bar');
        if (!progressBar) return;

        let ticking = false;

        function updateProgressBar() {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight;
            const clientHeight = document.documentElement.clientHeight;
            const maxScroll = scrollHeight - clientHeight;

            if (maxScroll <= 0) {
                progressBar.style.transform = 'scaleX(0)';
                ticking = false;
                return;
            }

            const progress = Math.min(1, Math.max(0, scrollTop / maxScroll));
            progressBar.style.transform = `scaleX(${progress})`;
            ticking = false;
        }

        function onScroll() {
            if (!ticking) {
                requestAnimationFrame(updateProgressBar);
                ticking = true;
            }
        }

        // Initial render
        updateProgressBar();

        // Passive scroll listener for better performance
        window.addEventListener('scroll', onScroll, { passive: true });

        // Also update on resize (viewport height changes)
        window.addEventListener('resize', onScroll, { passive: true });
    })();

    // Like Button Toggle
    (function() {
        const likeButton = document.getElementById('like-button');
        if (!likeButton) return;
        
        const likesCountElement = document.getElementById('likes-count');
        const heartFilled = document.getElementById('heart-filled');
        const heartOutline = document.getElementById('heart-outline');
        const postId = likeButton.dataset.postId;
        const storageKey = `hasLiked_${postId}`;
        
        let isLiked = localStorage.getItem(storageKey) === 'true';
        let isProcessing = false;
        
        function updateHeartVisual() {
            if (isLiked) {
                heartFilled.classList.remove('hidden');
                heartOutline.classList.add('hidden');
                likesCountElement.classList.add('bg-red-100');
                likesCountElement.classList.remove('bg-gray-100');
            } else {
                heartFilled.classList.add('hidden');
                heartOutline.classList.remove('hidden');
                likesCountElement.classList.remove('bg-red-100');
                likesCountElement.classList.add('bg-gray-100');
            }
        }
        
        updateHeartVisual();
        
        likeButton.addEventListener('click', async function() {
            if (isProcessing) return;
            isProcessing = true;
            
            const action = isLiked ? 'unlike' : 'like';
            let currentCountText = likesCountElement.textContent;
            let currentCount = currentCountText.includes('+') ? 99 : parseInt(currentCountText) || 0;
            
            if (action === 'like') {
                currentCount++;
                isLiked = true;
            } else {
                currentCount = Math.max(0, currentCount - 1);
                isLiked = false;
            }
            
            likesCountElement.textContent = currentCount > 99 ? '99+' : currentCount;
            updateHeartVisual();
            
            likeButton.classList.add('scale-110');
            setTimeout(() => likeButton.classList.remove('scale-110'), 200);
            
            try {
                const response = await fetch(`/api/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ action: action })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    likesCountElement.textContent = data.likes_count > 99 ? '99+' : data.likes_count;
                    if (action === 'like') {
                        localStorage.setItem(storageKey, 'true');
                        isLiked = true;
                    } else {
                        localStorage.removeItem(storageKey);
                        isLiked = false;
                    }
                    updateHeartVisual();
                } else {
                    if (action === 'like') {
                        currentCount--;
                        isLiked = false;
                    } else {
                        currentCount++;
                        isLiked = true;
                    }
                    likesCountElement.textContent = currentCount > 99 ? '99+' : currentCount;
                    updateHeartVisual();
                }
            } catch (error) {
                console.error('Error:', error);
                if (action === 'like') {
                    currentCount--;
                    isLiked = false;
                } else {
                    currentCount++;
                    isLiked = true;
                }
                likesCountElement.textContent = currentCount > 99 ? '99+' : currentCount;
                updateHeartVisual();
            } finally {
                isProcessing = false;
            }
        });
    })();
</script>
@endpush
