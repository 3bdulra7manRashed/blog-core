{{-- Hero Section — Asymmetric Layout --}}
<section class="relative min-h-[60vh] lg:min-h-[calc(100vh-5rem)] flex items-center overflow-hidden">

    {{-- Background Image (anchored right so person stays visible) --}}
    @if($hero['image'])
        <div class="absolute inset-0 z-0">
            <img src="{{ str_starts_with($hero['image'], 'http') ? $hero['image'] : asset('storage/' . $hero['image']) }}"
                alt="{{ $hero['title'] }}" class="w-full h-full object-cover object-right md:object-[80%_center]">
        </div>
    @endif

    {{-- Directional Gradient Overlay (RTL: transparent on right → solid dark on left) --}}
    <div class="absolute inset-0 z-[1]
        @if($hero['image'])
            bg-gradient-to-l from-transparent via-[#0F5D56]/95 to-[#0F766E]
        @else
            bg-gradient-to-br from-brand-primary via-brand-secondary to-brand-accent
        @endif
    "></div>

    {{-- Content Container — pushed to RTL-left (visual right in LTR) --}}
    <div class="container mx-auto px-6 relative z-10 flex items-center justify-end h-full py-16 lg:py-0">
        <div class="w-full md:w-3/5 lg:w-1/2 text-right">

            {{-- Title --}}
            <h1
                class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-serif font-bold text-white mb-5 leading-tight drop-shadow-lg">
                {{ $hero['title'] }}
            </h1>

            {{-- Subtitle --}}
            @if($hero['subtitle'])
                <p class="text-xl md:text-2xl lg:text-3xl text-white/80 leading-relaxed mb-8 max-w-xl whitespace-pre-line">
                    {{ $hero['subtitle'] }}
                </p>
            @endif

            {{-- CTA Button --}}
            @include('landing::front.partials.cta')
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>