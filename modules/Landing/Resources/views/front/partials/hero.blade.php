{{-- Hero Section — Responsive: Stacked Mobile / Asymmetric Desktop --}}
<section class="relative overflow-hidden bg-[#0F766E] min-h-[calc(100vh-5rem)] flex flex-col lg:block">

    {{-- ═══ DESKTOP LAYOUT (lg+): Classic asymmetric hero ═══ --}}
    <div class="hidden lg:block relative h-full min-h-[calc(100vh-5rem)]">
        
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
                bg-gradient-to-br from-[#0F766E] via-[#0F5D56] to-[#14B8A6]
            @endif
        "></div>

        {{-- Content Container — pushed to RTL-left (visual right in LTR) --}}
        <div class="container mx-auto px-6 relative z-10 flex items-center justify-end h-full min-h-[calc(100vh-5rem)] py-16">
            <div class="w-full md:w-3/5 lg:w-1/2 text-right">

                {{-- Title --}}
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-serif font-bold text-white mb-8 leading-tight drop-shadow-lg">
                    {{ $hero['title'] }}
                </h1>

                {{-- Subtitle --}}
                @if($hero['subtitle'])
                    <p class="text-xl md:text-2xl lg:text-3xl text-white/80 leading-[2] mb-12 max-w-xl whitespace-pre-line">
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
        
    </div>


    {{-- ═══ MOBILE LAYOUT (<lg): Clean vertical stack, constrained to Viewport ═══ --}} 
    <div class="lg:hidden flex flex-col flex-1 h-full min-h-[calc(100vh-5rem)] relative">
        
        {{-- Overall Background Gradient for Mobile --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#0F5D56] to-[#0F766E] z-0"></div>

        {{-- Top Section: Text & CTA --}}
        <div class="relative z-10 flex-none flex flex-col justify-end px-4 pt-12 pb-2 text-center">
            
            <h1 class="text-5xl sm:text-6xl font-serif font-bold text-white mb-8 leading-[1.3] drop-shadow-md">
                {{ $hero['title'] }}
            </h1>

            @if($hero['subtitle'])
                <p class="text-xl sm:text-2xl font-medium text-white/95 leading-[1.8] mb-10 w-full px-2 mx-auto whitespace-pre-line">
                    {{ $hero['subtitle'] }}
                </p>
            @endif

            {{-- CTA Button --}}
            @if(!empty($cta['text']) && !empty($cta['link']))
                <div class="flex justify-center mt-2 mb-4">
                    <a href="{{ $cta['link'] }}"
                        class="px-8 py-3 text-base bg-white text-[#0F766E] font-bold rounded-full transition-all duration-300 hover:-translate-y-1 hover:bg-gray-100 shadow-xl active:scale-95 shrink-0">
                        {{ $cta['text'] }}
                    </a>
                </div>
            @endif

        </div>

        {{-- Bottom Section: Subject Image --}}
        @php $mobileImagePath = $hero['mobile_image'] ?: $hero['image']; @endphp
        @if($mobileImagePath)
            <div class="relative w-full shrink-0 h-[38vh] flex justify-center items-end overflow-hidden pt-2 mt-auto">
                
                {{-- Subtle top gradient for smooth transition --}}
                <div class="absolute top-0 left-0 right-0 h-16 bg-gradient-to-b from-transparent to-transparent z-10 pointer-events-none"></div>

                <img src="{{ str_starts_with($mobileImagePath, 'http') ? $mobileImagePath : asset('storage/' . $mobileImagePath) }}"
                    alt="{{ $hero['title'] }}" class="w-full h-full object-contain object-bottom relative z-20">
            </div>
        @endif

    </div>

</section>