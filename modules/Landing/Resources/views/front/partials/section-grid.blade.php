{{--
    Reusable Section Grid Partial
    
    Usage:
    @include('landing::front.partials.section-grid', [
        'title'       => 'Section Title',
        'subtitle'    => 'Optional subtitle text',
        'items'       => $collection,        // Collection of post items
        'viewAllUrl'  => '/category/slug',   // Optional "view all" link
        'viewAllText' => 'عرض المزيد',       // Optional custom link text
        'bgClass'     => 'bg-gray-50',       // Optional background class
    ])
--}}
<section class="py-16 {{ $bgClass ?? '' }}">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Section Header --}}
        @if(!empty($title))
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-brand-primary mb-4">{{ $title }}</h2>
                @if(!empty($subtitle))
                    <p class="text-gray-600 max-w-2xl mx-auto">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        {{-- 3-Column Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($items as $post)
                @include('landing::front.partials.post-card', ['post' => $post])
            @endforeach
        </div>

        {{-- View All Link --}}
        @if(!empty($viewAllUrl))
            <div class="text-center mt-8">
                <a href="{{ $viewAllUrl }}" class="inline-flex items-center text-brand-accent hover:text-brand-primary font-medium transition-colors">
                    {{ $viewAllText ?? 'عرض الكل' }}
                    <svg class="w-5 h-5 mr-2 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        @endif

    </div>
</section>
