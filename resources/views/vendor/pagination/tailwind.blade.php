{{--
Modern "Floating Pills" Pagination

Design:
- Separated circular pills with gaps
- Soft shadows and hover lift effects
- Brand accent color for active state
- RTL-native with space-x-reverse
- Mobile: Simplified (prev/current/next only)
--}}
@php
    $toArabic = function ($number) {
        return strtr($number, ['0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩']);
    };
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center gap-4"
        dir="rtl">

        {{-- Results Count (Secondary info) --}}
        <p class="text-sm text-gray-400">
            عرض
            <span class="font-medium text-gray-600">{{ $toArabic($paginator->firstItem() ?? 0) }}</span>
            –
            <span class="font-medium text-gray-600">{{ $toArabic($paginator->lastItem() ?? 0) }}</span>
            من
            <span class="font-medium text-gray-600">{{ $toArabic($paginator->total()) }}</span>
        </p>

        {{-- Pagination Controls --}}
        <div class="flex items-center justify-center gap-2">

            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="الصفحة السابقة"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-gray-600 shadow-sm border border-gray-100 hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            <div class="flex items-center gap-1.5">
                @foreach ($elements as $element)
                    {{-- Ellipsis --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-10 h-10 text-gray-400 text-sm">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Page Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Active Page (Always visible) --}}
                                <span aria-current="page"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-brand-accent text-white font-bold text-sm shadow-lg shadow-brand-accent/30 cursor-default">
                                    {{ $toArabic($page) }}
                                </span>
                            @else
                                {{-- Inactive Pages (Hidden on mobile, visible on desktop) --}}
                                <a href="{{ $url }}" aria-label="صفحة {{ $toArabic($page) }}"
                                    class="hidden sm:inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-gray-600 font-medium text-sm shadow-sm border border-gray-100 hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 transition-all duration-200">
                                    {{ $toArabic($page) }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="الصفحة التالية"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-gray-600 shadow-sm border border-gray-100 hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @endif

        </div>

        {{-- Mobile Page Indicator (visible only on mobile when there are hidden pages) --}}
        @if ($paginator->lastPage() > 1)
            <p class="sm:hidden text-xs text-gray-400">
                صفحة {{ $toArabic($paginator->currentPage()) }} من {{ $toArabic($paginator->lastPage()) }}
            </p>
        @endif

    </nav>
@endif