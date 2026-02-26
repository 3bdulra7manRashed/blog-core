<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    {{-- Essential Meta Tags --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="var(--brand-primary)">
    <meta name="googlebot" content="index, follow">

    {{-- Google Search Console Verification --}}

    {{--
    Dynamic Title Generation
    Priority: 1) SeoManager data 2) @section('title') 3) Site defaults
    --}}
    <title>
        @if($seoManager->getData())
            {{ $seoManager->getTitle() }}
        @elseif(View::hasSection('title'))
            @yield('title') | {{ config('branding.site_name') }}
        @else
            {{ config('branding.site_name') }} | {{ config('branding.tagline') }}
        @endif
    </title>

    {{--
    SEO Meta Tags via SeoManager
    - Basic SEO: Always rendered when feature('seo') is true
    - Advanced SEO: OpenGraph, Twitter, JSON-LD when feature('advanced_seo') is true
    - Falls back to advanced-seo::head for backward compatibility
    --}}
    @if($seoManager->getData())
        {!! $seoManager->render() !!}
    @else
        @includeWhen(feature('advanced_seo'), 'advanced-seo::head')
    @endif

    {{-- Additional Meta Tags (Allow pages to override via @push('meta')) --}}
    @stack('meta')

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/favicons/fav-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicons/fav-180.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Scripts --}}
    @vite(['resources/themes/bayan/assets/css/app.css', 'resources/themes/bayan/assets/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Structured Data (Schema.org JSON-LD) --}}
    @yield('schema')
    @include('partials.schema')

    {{-- Google Analytics (GA4) - Uncomment and replace G-XXXXXXXXXX with your tracking ID --}}
    {{--
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
    --}}

    {{-- Page-specific styles --}}
    @stack('styles')
</head>

<body class="font-sans antialiased bg-white text-brand-primary flex flex-col min-h-screen">
    {{-- Skip to Content Link (Accessibility) --}}
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:right-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-brand-accent focus:text-white focus:rounded-md focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-accent">
        تخطّي إلى المحتوى
    </a>
    <!-- Main Header Section (Glassmorphism UI) -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300"
        x-data="{ mobileMenuOpen: false, articlesOpen: false }"
        x-init="$watch('mobileMenuOpen', value => { if (value) { setTimeout(() => document.getElementById('mobile-menu-close')?.focus(), 100); } })">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="flex items-center justify-between h-20">

                <!-- ═══ Right Column: Logo (flex-1, justify-start for RTL) ═══ -->
                <div class="flex-1 flex justify-start">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <span class="text-[var(--brand-primary)] font-serif font-bold text-2xl tracking-tight">
                            {{ config('branding.site_name') }}
                        </span>
                        <span
                            class="w-2.5 h-2.5 bg-[var(--brand-accent)] rounded-full inline-block group-hover:scale-125 transition-transform duration-300"></span>
                    </a>
                </div>

                <!-- ═══ Center Column: Navigation Links (flex-none, perfectly centered) ═══ -->
                <div class="flex-none hidden lg:flex justify-center gap-6 lg:gap-8">
                    <nav class="flex gap-6 lg:gap-8 items-center">
                        <a href="{{ route('home') }}"
                            class="text-base transition-colors duration-200 {{ request()->routeIs('home') || request()->routeIs('post.*') || request()->routeIs('category.*') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                            المقالات
                        </a>

                        @if(feature('vod.video'))
                            <a href="{{ route('videos.index') }}"
                                class="text-base transition-colors duration-200 {{ request()->routeIs('videos.*') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                                مكتبة الفيديو
                            </a>
                        @endif

                        @if(feature('vod.audio'))
                            <a href="{{ route('audios.index') }}"
                                class="text-base transition-colors duration-200 {{ request()->routeIs('audios.*') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                                الصوتيات
                            </a>
                        @endif

                        @if(feature('books') && ($hasBooks ?? false))
                            <a href="{{ route('books.index') }}"
                                class="text-base transition-colors duration-200 {{ request()->routeIs('books.*') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                                الإصدارات
                            </a>
                        @endif

                        @if(feature('khutab'))
                            <a href="{{ route('khutab.index') }}"
                                class="text-base transition-colors duration-200 {{ request()->routeIs('khutab.*') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                                الخطب
                            </a>
                        @endif

                        <a href="{{ route('about') }}"
                            class="text-base transition-colors duration-200 {{ request()->routeIs('about') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                            عني
                        </a>

                        @if(feature('contact') && Route::has('contact'))
                            <a href="{{ route('contact') }}"
                                class="text-base transition-colors duration-200 {{ request()->routeIs('contact') ? 'text-[var(--brand-primary)] font-bold relative after:absolute after:-bottom-[6px] after:left-0 after:w-full after:h-0.5 after:bg-[var(--brand-primary)]' : 'text-gray-600 font-medium hover:text-[var(--brand-accent)]' }}">
                                تواصل معي
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- ═══ Left Column: Icons & Actions (flex-1, justify-end) ═══ -->
                <div class="flex-1 hidden lg:flex justify-end items-center gap-4">
                    <!-- Search Form -->
                    <div class="relative flex items-center" id="search-container">
                        <form id="search-form" action="{{ route('search') }}" method="GET"
                            class="flex items-center flex-row-reverse transition-all duration-300">
                            <button type="button" id="search-toggle"
                                class="text-gray-500 hover:text-[var(--brand-primary)] transition-colors z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            <div id="search-input-wrapper"
                                class="w-0 overflow-hidden transition-all duration-300 ease-in-out">
                                <input type="text" name="q" placeholder="ابحث هنا..."
                                    class="w-48 px-3 py-1 text-sm border-b border-gray-300 focus:border-[var(--brand-primary)] focus:outline-none bg-transparent text-gray-700 placeholder-gray-400 mr-2 text-right dir-rtl">
                            </div>
                        </form>
                    </div>

                    <!-- X (Twitter) -->
                    @if(config('branding.social.twitter'))
                        <a href="{{ config('branding.social.twitter') }}" target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-[var(--brand-primary)] transition-colors duration-200" title="X (Twitter)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    @endif

                    <!-- LinkedIn -->
                    @if(config('branding.social.linkedin'))
                        <a href="{{ config('branding.social.linkedin') }}" target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-[var(--brand-primary)] transition-colors duration-200" title="LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                    @endif

                    <!-- Sidebar Toggle -->
                    <button id="sidebar-toggle"
                        class="text-gray-400 hover:text-[var(--brand-primary)] transition-colors duration-200 bg-transparent border-none p-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile: Hamburger Button -->
                <button class="md:hidden text-gray-600 hover:text-[var(--brand-primary)] transition-colors p-2"
                    id="mobile-menu-button"
                    @click="mobileMenuOpen = true; $nextTick(() => { document.getElementById('mobile-menu-close').focus(); })"
                    aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen" aria-label="فتح القائمة">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Off-Canvas Menu -->
        <!-- Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
            @keydown.escape.window="mobileMenuOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-50 md:hidden"
            style="display: none;" aria-hidden="true"></div>

        <!-- Sidebar Panel -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full" @keydown.escape.window="mobileMenuOpen = false" id="mobile-menu"
            role="dialog" aria-modal="true" aria-label="قائمة الموقع"
            class="fixed top-0 right-0 h-full w-72 sm:w-80 bg-white shadow-xl z-50 overflow-y-auto md:hidden"
            style="display: none;" x-ref="mobileMenu">
            <!-- Close Button -->
            <div class="flex justify-start items-center p-4 border-b border-gray-100">
                <button id="mobile-menu-close" @click="mobileMenuOpen = false"
                    class="p-2 text-gray-600 hover:text-brand-accent transition-colors rounded-md hover:bg-gray-100"
                    aria-label="إغلاق القائمة">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Items -->
            <nav class="py-4" @click.away="mobileMenuOpen = false">
                <div class="flex flex-col">
                    <!-- المقالات (Articles) - Direct Link -->
                    <a href="{{ route('home') }}"
                        class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('home') || request()->routeIs('post.*') ? 'text-brand-accent bg-gray-50' : '' }}"
                        @click="mobileMenuOpen = false">
                        المقالات
                    </a>

                    <!-- Video Library -->
                    @if(feature('vod.video'))
                        <a href="{{ route('videos.index') }}"
                            class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('videos.*') ? 'text-brand-accent bg-gray-50' : '' }}"
                            @click="mobileMenuOpen = false">
                            مكتبة الفيديو
                        </a>
                    @endif

                    <!-- Audio Library -->
                    @if(feature('vod.audio'))
                        <a href="{{ route('audios.index') }}"
                            class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('audios.*') ? 'text-brand-accent bg-gray-50' : '' }}"
                            @click="mobileMenuOpen = false">
                            الصوتيات
                        </a>
                    @endif

                    <!-- الإصدارات (Books) -->
                    @if(feature('books') && ($hasBooks ?? false))
                        <a href="{{ route('books.index') }}"
                            class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('books.*') ? 'text-brand-accent bg-gray-50' : '' }}"
                            @click="mobileMenuOpen = false">
                            الإصدارات
                        </a>
                    @endif

                    <!-- الخطب (Khutab) -->
                    @if(feature('khutab'))
                        <a href="{{ route('khutab.index') }}"
                            class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('khutab.*') ? 'text-brand-accent bg-gray-50' : '' }}"
                            @click="mobileMenuOpen = false">
                            الخطب
                        </a>
                    @endif

                    <!-- عني (About) -->
                    <a href="{{ route('about') }}"
                        class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('about') ? 'text-brand-accent bg-gray-50' : '' }}"
                        @click="mobileMenuOpen = false">
                        عني
                    </a>

                    <!-- تواصل معي (Contact) -->
                    @if(feature('contact') && Route::has('contact'))
                        <a href="{{ route('contact') }}"
                            class="block px-4 py-3 text-right text-base font-medium text-gray-800 hover:bg-gray-50 hover:text-brand-accent transition-colors min-h-[3rem] flex items-center border-b border-gray-100 {{ request()->routeIs('contact') ? 'text-brand-accent bg-gray-50' : '' }}"
                            @click="mobileMenuOpen = false">
                            تواصل معي
                        </a>
                    @endif

                    <!-- Sidebar Widgets (Mobile Only) -->
                    <div class="px-4 py-4 space-y-4 border-b border-gray-100">
                        <!-- Search -->
                        <div class="bg-brand-secondary p-6 rounded-lg">
                            <h4 class="text-base font-semibold mb-3 text-right">البحث</h4>
                            <form action="{{ route('search') }}" method="GET" @click.stop>
                                <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-right focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent" />
                            </form>
                        </div>

                        <!-- Categories -->
                        @if(isset($categories) && $categories->count() > 0)
                            <div class="bg-brand-secondary p-6 rounded-lg">
                                <h4 class="text-base font-semibold mb-3 text-right">الأقسام</h4>
                                <ul class="space-y-2 text-right">
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ route('category.show', $category->slug) }}"
                                                class="flex justify-between items-center py-2 hover:text-brand-accent transition-colors"
                                                @click="mobileMenuOpen = false">
                                                <span class="text-sm text-gray-500">({{ $category->posts_count ?? 0 }})</span>
                                                <span class="text-sm font-medium">{{ $category->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Latest Posts -->
                        @if(isset($recentPosts) && $recentPosts->count() > 0)
                            <div class="bg-brand-secondary p-6 rounded-lg">
                                <h4 class="text-base font-semibold mb-3 text-right">أحدث المقالات</h4>
                                <ul class="space-y-3 text-right">
                                    @foreach($recentPosts as $recentPost)
                                        <li>
                                            <a href="{{ route('post.show', $recentPost->slug) }}" class="block group"
                                                @click="mobileMenuOpen = false">
                                                <div
                                                    class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                                    {{ Str::limit($recentPost->title, 60) }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $recentPost->published_at->format('Y/m/d') }}
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Most Liked Posts -->
                        @if(isset($mostLikedPosts) && $mostLikedPosts->count() > 0)
                            <div class="bg-brand-secondary p-6 rounded-lg">
                                <h4 class="text-base font-semibold mb-3 text-right">المقالات الأكثر إعجاباً</h4>
                                <ul class="space-y-3 text-right">
                                    @foreach($mostLikedPosts as $likedPost)
                                        <li>
                                            <a href="{{ route('post.show', $likedPost->slug) }}" class="block group"
                                                @click="mobileMenuOpen = false">
                                                <div
                                                    class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                                    {{ Str::limit($likedPost->title, 60) }}
                                                </div>
                                                <div class="text-xs text-gray-400 flex items-center gap-1 justify-end">
                                                    <span>{{ $likedPost->likes_count ?? 0 }} إعجاب</span>
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                    </svg>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Most Read Posts -->
                        @if(isset($mostReadPosts) && $mostReadPosts->count() > 0)
                            <div class="bg-brand-secondary p-6 rounded-lg">
                                <h4 class="text-base font-semibold mb-3 text-right">المقالات الأكثر قراءة</h4>
                                <ul class="space-y-3 text-right">
                                    @foreach($mostReadPosts as $readPost)
                                        <li>
                                            <a href="{{ route('post.show', $readPost->slug) }}" class="block group"
                                                @click="mobileMenuOpen = false">
                                                <div
                                                    class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                                    {{ Str::limit($readPost->title, 60) }}
                                                </div>
                                                <div class="text-xs text-gray-400 flex items-center gap-1 justify-end">
                                                    <span>{{ $readPost->views ?? 0 }} مشاهدة</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>
            </nav>
        </div>
    </header>

    <!-- No-JS Fallback Menu (shows when JavaScript is disabled) -->
    <noscript>
        <div class="md:hidden border-t border-gray-200 bg-white">
            <div class="container mx-auto px-4 py-4 flex flex-col space-y-4">
                <details class="border-b border-gray-100 pb-4">
                    <summary class="text-base font-medium text-gray-800 cursor-pointer list-none py-2">
                        المقالات
                    </summary>
                    <div class="mt-2 pr-4 space-y-2">
                        <a href="{{ route('home') }}"
                            class="block text-sm text-gray-700 hover:text-brand-accent py-2">الأحدث</a>
                        <a href="{{ route('posts.most-liked') }}"
                            class="block text-sm text-gray-700 hover:text-brand-accent py-2">المقالات الأكثر إعجاباً</a>
                        <a href="{{ route('posts.most-read') }}"
                            class="block text-sm text-gray-700 hover:text-brand-accent py-2">المقالات الأكثر قراءة</a>
                    </div>
                </details>
                <a href="{{ route('about') }}"
                    class="text-base font-medium text-gray-800 hover:text-brand-accent py-2">عني</a>
                @if(feature('contact') && Route::has('contact'))
                    <a href="{{ route('contact') }}"
                        class="text-base font-medium text-gray-800 hover:text-brand-accent py-2">تواصل معي</a>
                @endif

                <!-- Sidebar Widgets (No-JS Fallback) -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <!-- Search -->
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <h4 class="text-base font-semibold mb-3 text-right">البحث</h4>
                        <form action="{{ route('search') }}" method="GET">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-right focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent" />
                        </form>
                    </div>

                    <!-- Categories -->
                    @if(isset($categories) && $categories->count() > 0)
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h4 class="text-base font-semibold mb-3 text-right">الأقسام</h4>
                            <ul class="space-y-2 text-right">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('category.show', $category->slug) }}"
                                            class="flex justify-between items-center py-2 hover:text-brand-accent transition-colors">
                                            <span class="text-sm text-gray-500">({{ $category->posts_count ?? 0 }})</span>
                                            <span class="text-sm font-medium">{{ $category->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Latest Posts -->
                    @if(isset($recentPosts) && $recentPosts->count() > 0)
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h4 class="text-base font-semibold mb-3 text-right">أحدث المقالات</h4>
                            <ul class="space-y-3 text-right">
                                @foreach($recentPosts as $recentPost)
                                    <li>
                                        <a href="{{ route('post.show', $recentPost->slug) }}" class="block group">
                                            <div
                                                class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                                {{ Str::limit($recentPost->title, 60) }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $recentPost->published_at->format('Y/m/d') }}
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </noscript>

    <!-- Main Content -->
    <main id="main-content" class="flex-grow">
        {{-- Flash Messages Area --}}
        @if(session('success'))
            <div class="container mx-auto px-4 mt-6 max-w-5xl">
                <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative"
                    role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    x-transition.duration.500ms>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false"
                            class="text-green-700 hover:text-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mx-auto px-4 mt-6 max-w-5xl">
                <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded shadow-sm relative" role="alert"
                    x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false"
                            class="text-red-700 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.sidebar')

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 mt-20">
        <div class="container mx-auto px-4 max-w-5xl">

            <!-- Newsletter Section -->
            @module('Newsletter')
            @if(!$isPostPage)
                <div class="py-12 border-b border-gray-200">
                    <div class="max-w-2xl mx-auto">
                        <x-newsletter-form variant="compact" />
                    </div>
                </div>
            @endif
            @endmodule

            <!-- Footer Bottom -->
            <div class="py-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Copyright -->
                    <div class="text-center md:text-right order-2 md:order-1">
                        <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('branding.site_name') }}. جميع
                            الحقوق محفوظة.</p>
                    </div>

                    <!-- Social Links -->
                    <div class="flex flex-col items-center gap-3 order-1 md:order-2">
                        <p class="text-sm font-medium text-gray-400">تابعني</p>
                        <div class="flex items-center gap-4">
                            <!-- X (Twitter) -->
                            @if(config('branding.social.twitter'))
                                <a href="{{ config('branding.social.twitter') }}" target="_blank" rel="noopener noreferrer"
                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-brand-accent hover:border-brand-accent transition-all shadow-sm hover:shadow">
                                    <span class="sr-only">X (Twitter)</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                </a>
                            @endif
                            <!-- LinkedIn -->
                            @if(config('branding.social.linkedin'))
                                <a href="{{ config('branding.social.linkedin') }}" target="_blank" rel="noopener noreferrer"
                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-brand-accent hover:border-brand-accent transition-all shadow-sm hover:shadow">
                                    <span class="sr-only">LinkedIn</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Focus trap for mobile menu - simplified approach
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            if (mobileMenu) {
                // Handle Tab key for focus trap
                mobileMenu.addEventListener('keydown', function (e) {
                    if (e.key !== 'Tab') return;

                    const focusableElements = mobileMenu.querySelectorAll(
                        'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
                    );
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];

                    if (e.shiftKey) {
                        // Shift + Tab
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement?.focus();
                        }
                    } else {
                        // Tab
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement?.focus();
                        }
                    }
                });
            }
        });

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('close-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('hidden');

            // In RTL, the sidebar should come from the left if the button is on the left?
            // The third screenshot shows sidebar on the LEFT.
            // So we need to change translate-x-full to -translate-x-full for left side sidebar
            // Or adjust the sidebar partial to be on the left.

            if (isHidden) {
                sidebar.classList.remove('hidden');
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    sidebar.classList.remove('-translate-x-full'); // Changed for Left Sidebar
                    overlay.classList.remove('opacity-0');
                }, 10);
            } else {
                sidebar.classList.add('-translate-x-full'); // Changed for Left Sidebar
                overlay.classList.add('opacity-0');
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                    overlay.classList.add('hidden');
                }, 300);
            }
        }

        sidebarToggle?.addEventListener('click', toggleSidebar);
        closeSidebar?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);

        // --- 1. SEARCH LOGIC ---
        const searchToggle = document.getElementById('search-toggle');
        const searchInputWrapper = document.getElementById('search-input-wrapper');
        const searchContainer = document.getElementById('search-container');
        const searchForm = document.getElementById('search-form'); // Select the form

        searchToggle?.addEventListener('click', (e) => {
            e.preventDefault();
            if (searchInputWrapper.classList.contains('w-0')) {
                // OPEN
                searchInputWrapper.classList.remove('w-0');
                searchInputWrapper.classList.add('w-48');
                searchForm.classList.add('gap-3'); // Add Gap
                setTimeout(() => searchInputWrapper.querySelector('input').focus(), 300);
            } else {
                // CLOSE
                searchInputWrapper.classList.remove('w-48');
                searchInputWrapper.classList.add('w-0');
                searchForm.classList.remove('gap-3'); // Remove Gap
            }
        });

        document.addEventListener('click', (e) => {
            if (searchContainer && !searchContainer.contains(e.target) && !searchInputWrapper.classList.contains('w-0')) {
                searchInputWrapper.classList.remove('w-48');
                searchInputWrapper.classList.add('w-0');
                searchForm.classList.remove('gap-3'); // Remove Gap
            }
        });

        // --- 2. STICKY MENU LOGIC ---
        const stickyMenuBtn = document.getElementById('sticky-menu-btn');
        const stickyMenuBtnContainer = document.getElementById('sticky-menu-btn-container');
        let isStickyVisible = false;

        // Function to handle Sidebar toggle (reused)
        stickyMenuBtn?.addEventListener('click', toggleSidebar);

        window.addEventListener('scroll', function () {
            if (window.scrollY > 150) {
                // SHOW BUTTON - Expand from 0 to full width
                if (!isStickyVisible) {
                    isStickyVisible = true;

                    // Expand width and fade in
                    stickyMenuBtnContainer.classList.remove('w-0', 'opacity-0', 'pointer-events-none');
                    stickyMenuBtnContainer.classList.add('w-8', 'opacity-100', 'pointer-events-auto');
                }
            } else {
                // HIDE BUTTON - Collapse to 0 width
                if (isStickyVisible) {
                    isStickyVisible = false;

                    // Collapse width and fade out
                    stickyMenuBtnContainer.classList.remove('w-8', 'opacity-100', 'pointer-events-auto');
                    stickyMenuBtnContainer.classList.add('w-0', 'opacity-0', 'pointer-events-none');
                }
            }
        });

        // Web Share API
        function sharePost(title, url) {
            if (navigator.share) {
                navigator.share({
                    title: title,
                    url: url
                }).catch(console.error);
            } else {
                // Fallback - Copy to clipboard or simple alert
                navigator.clipboard.writeText(url).then(() => {
                    alert('تم نسخ الرابط!');
                });
            }
        }
    </script>

    @stack('scripts')
</body>

</html>