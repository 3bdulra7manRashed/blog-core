{{-- Hero Section — Responsive: Stacked Mobile / Asymmetric Desktop --}}
@php
    // ── Resolve theme-specific Hero background settings ──
    $activeTheme = theme_name();
    $bgType = setting("theme_{$activeTheme}_hero_bg_type", 'solid');
    $color1 = setting("theme_{$activeTheme}_hero_bg_color_1", '#0F766E');
    $color2 = setting("theme_{$activeTheme}_hero_bg_color_2", '#14B8A6');
    $angle  = (int) setting("theme_{$activeTheme}_hero_bg_angle", 135);

    // Derive secondary shade from color1 (darken by mixing with black) for overlays
    // We'll use color1 with opacity for the overlay gradient effect
    $color1Dark = $color1 . 'F2'; // ~95% opacity variant for overlay via/to stops

    // Build the inline style for the main section background (solid fallback)
    $sectionBgStyle = $bgType === 'gradient'
        ? "background: linear-gradient({$angle}deg, {$color1}, {$color2});"
        : "background-color: {$color1};";

    // Build the desktop overlay gradient style (directional overlay on top of image)
    $desktopOverlayWithImage    = "background: linear-gradient(to left, transparent, {$color1}F2 50%, {$color1});";
    $desktopOverlayWithoutImage = "background: linear-gradient(to bottom right, {$color1}, {$color1}E6 50%, {$color2});";

    // Build the mobile background gradient style
    $mobileBgStyle = "background: linear-gradient(to bottom, {$color1}E6, {$color1});";
@endphp
<section class="relative overflow-hidden min-h-[calc(100vh-5rem)] flex flex-col lg:block" style="{{ $sectionBgStyle }}">

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
        <div class="absolute inset-0 z-[1]"
            style="{{ $hero['image'] ? $desktopOverlayWithImage : $desktopOverlayWithoutImage }}"
        ></div>

        {{-- Content Container — pushed to RTL-left (visual right in LTR) --}}
        <div class="container mx-auto px-6 relative z-10 flex items-center justify-end h-full min-h-[calc(100vh-5rem)] py-16">
            <div class="w-full md:w-3/5 lg:w-1/2 text-right">

                {{-- Title --}}
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-sakkal font-bold text-white mb-8 leading-tight drop-shadow-lg">
                    {{ $hero['title'] }}
                </h1>

                {{-- Subtitle --}}
                @if($hero['subtitle'])
                    <p class="text-xl md:text-2xl lg:text-3xl font-sakkal text-white/80 leading-[2] mb-12 max-w-xl whitespace-pre-line">
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
        <div class="absolute inset-0 z-0" style="{{ $mobileBgStyle }}"></div>

        {{-- Top Section: Text & CTA --}}
        <div class="relative z-10 flex-none flex flex-col justify-end px-4 pt-12 pb-2 text-center">
            
            <h1 class="text-5xl sm:text-6xl font-sakkal font-bold text-white mb-8 leading-[1.3] drop-shadow-md">
                {{ $hero['title'] }}
            </h1>

            @if($hero['subtitle'])
                <p class="text-xl sm:text-2xl font-sakkal font-medium text-white/95 leading-[1.8] mb-10 w-full px-2 mx-auto whitespace-pre-line">
                    {{ $hero['subtitle'] }}
                </p>
            @endif

            {{-- CTA Button --}}
            @if(!empty($cta['text']) && !empty($cta['link']))
                <div class="flex justify-center mt-2 mb-4">
                    <a href="{{ $cta['link'] }}"
                        class="px-8 py-3 text-base bg-white font-bold rounded-full transition-all duration-300 hover:-translate-y-1 hover:bg-gray-100 shadow-xl active:scale-95 shrink-0"
                        style="color: {{ $color1 }};">
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