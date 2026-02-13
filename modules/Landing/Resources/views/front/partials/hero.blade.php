{{-- Hero Section --}}
<section class="relative min-h-[78.2vh] flex items-center justify-center overflow-hidden">
    {{-- Background Image or Gradient --}}
    @if($hero['image'])
        <div class="absolute inset-0 z-0">
            <img src="{{ str_starts_with($hero['image'], 'http') ? $hero['image'] : asset('storage/' . $hero['image']) }}"
                alt="{{ $hero['title'] }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60"></div>
        </div>
    @else
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-brand-primary via-brand-secondary to-brand-accent"></div>
    @endif

    {{-- Content --}}
    <div class="relative z-10 container mx-auto px-4 text-center max-w-4xl py-16">
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6 leading-tight drop-shadow-lg">
            {{ $hero['title'] }}
        </h1>

        @if($hero['subtitle'])
            <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed drop-shadow">
                {{ $hero['subtitle'] }}
            </p>
        @endif

        {{-- CTA Buttons --}}
        @include('landing::front.partials.cta')
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>