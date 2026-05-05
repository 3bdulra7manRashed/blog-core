{{-- Hero Section — Responsive: Stacked Mobile / Asymmetric Desktop --}}
@php
    // ── Resolve theme-specific Hero background settings ──
    $activeTheme = theme_name();
    $bgType = setting("theme_{$activeTheme}_hero_bg_type", 'preset');
    
    $finalMobBg = '';
    $finalDeskBg = '';
    
    if ($bgType === 'solid') {
        $solidColor = setting("theme_{$activeTheme}_hero_bg_color_1", '#0F766E');
        // Trick: Format solid color as a flat gradient so it works with the bg-[image:...] utility
        $flatBg = "linear-gradient(0deg, {$solidColor}, {$solidColor})";
        $finalMobBg = $flatBg;
        $finalDeskBg = $flatBg;
        
        $overlayColor = $solidColor;
        $ctaPrimaryColor = $solidColor;
    } 
    elseif ($bgType === 'gradient') {
        $color1 = setting("theme_{$activeTheme}_hero_bg_color_1", '#0F766E');
        $color2 = setting("theme_{$activeTheme}_hero_bg_color_2", '#14B8A6');
        $angle = setting("theme_{$activeTheme}_hero_bg_angle", '135deg');
        // Ensure angle has 'deg' unit
        $angle = str_contains((string)$angle, 'deg') ? $angle : "{$angle}deg";
        
        // Force 180deg for mobile UX, use custom angle for desktop
        $finalMobBg = "linear-gradient(180deg, {$color1} 0%, {$color2} 100%)";
        $finalDeskBg = "linear-gradient({$angle}, {$color1} 0%, {$color2} 100%)";
        
        $overlayColor = $color1;
        $ctaPrimaryColor = $color1;
    } 
    else {
        // Preset logic
        $activePreset = setting("theme_{$activeTheme}_hero_bg_preset", 'premium_cinematic');
        $presets = [
            'institutional_teal' => [
                'mob' => 'linear-gradient(180deg, #2E6F89 0%, #5EA6A2 35%, #7DB39B 65%, #6B7FA7 100%)',
                'desk' => 'linear-gradient(135deg, #2E6F89 0%, #5EA6A2 35%, #7DB39B 65%, #6B7FA7 100%)',
                'primary' => '#2E6F89'
            ],
            'premium_cinematic' => [
                'mob' => 'radial-gradient(ellipse at 72% 18%, rgba(181, 231, 211, 0.50) 0%, rgba(181, 231, 211, 0.20) 28%, transparent 60%), linear-gradient(180deg, #2D6F8A 0%, #438CA0 35%, #647B89 72%, #7082AB 100%)',
                'desk' => 'radial-gradient(ellipse at 75% 55%, rgba(181, 231, 211, 0.45) 0%, rgba(181, 231, 211, 0.15) 35%, transparent 65%), linear-gradient(135deg, #2D6F8A 0%, #438CA0 45%, #7DB29E 75%, #7082AB 100%)',
                'primary' => '#2D6F8A'
            ],
        ];
        
        $selected = $presets[$activePreset] ?? $presets['premium_cinematic'];
        $finalMobBg = $selected['mob'];
        $finalDeskBg = $selected['desk'];
        
        $overlayColor = $selected['primary'];
        $ctaPrimaryColor = $selected['primary'];
    }

    // Build the desktop overlay gradient style (directional overlay on top of image)
    $desktopOverlayWithImage = "background: linear-gradient(to left, transparent, {$overlayColor}F2 50%, {$overlayColor});";
    $desktopOverlayWithoutImage = "background-image: {$finalDeskBg};";
@endphp
<section
    class="relative overflow-hidden min-h-[calc(100vh-5rem)] flex flex-col lg:block bg-[image:var(--bg-mob)] md:bg-[image:var(--bg-desk)]"
    style="--bg-mob: {{ $finalMobBg }}; --bg-desk: {{ $finalDeskBg }};">

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
            style="{{ $hero['image'] ? $desktopOverlayWithImage : $desktopOverlayWithoutImage }}"></div>

        {{-- Content Container — pushed to RTL-left (visual right in LTR) --}}
        <div
            class="container mx-auto px-6 relative z-10 flex items-center justify-end h-full min-h-[calc(100vh-5rem)] py-16">
            <div class="w-full md:w-3/5 lg:w-1/2 text-right">

                {{-- Title --}}
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-sakkal font-bold text-white mb-8 leading-tight drop-shadow-lg">
                    {{ $hero['title'] }}
                </h1>

                {{-- Subtitle --}}
                @if($hero['subtitle'])
                    <p
                        class="text-xl md:text-2xl lg:text-3xl font-sakkal text-white/80 leading-[2] mb-12 max-w-xl whitespace-pre-line">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3">
                </path>
            </svg>
        </div>

    </div>


    {{-- ═══ MOBILE LAYOUT (<lg): Premium personal-brand hero ═══ --}} <div
        class="lg:hidden flex flex-col relative overflow-hidden"
        style="min-height: calc(100vh - 70px);">

        {{-- ── Main Content Stack ── --}}
        <div class="relative z-10 flex flex-col items-center flex-1 px-5 text-center">

            {{-- Text Group: Vertically centered in available space --}}
            <div class="flex-grow flex flex-col justify-center items-center px-4 py-8 w-full">

                {{-- 1. Name / Title --}}
                <h1 class="text-[34px] font-sakkal font-bold text-white leading-[1.4]">
                    {{ $hero['title'] }}
                </h1>

                {{-- 2. Decorative Divider --}}
                <div class="w-12 h-1 bg-[#8DBA9D] rounded-full mx-auto mt-4"></div>

                {{-- 3. Description --}}
                @if($hero['subtitle'])
                    <p
                        class="text-[17px] font-sakkal text-white/80 leading-relaxed mt-5 w-[85%] max-w-xs mx-auto whitespace-pre-line">
                        {{ $hero['subtitle'] }}
                    </p>
                @endif

                {{-- 4. CTA Button — White pill (only renders when configured) --}}
                @if(!empty($cta['text']) && !empty($cta['link']))
                    <a href="{{ $cta['link'] }}"
                        class="inline-flex items-center justify-center w-[70%] h-[54px] mt-7 bg-white text-[#2F6D8A] font-medium text-base rounded-full shadow-lg transition-all duration-300 hover:shadow-xl active:scale-95">
                        {{ $cta['text'] }}
                    </a>
                @endif

            </div>

            {{-- 5. Portrait Image with Glow (Bottom anchored) --}}
            @php $mobileImagePath = $hero['mobile_image'] ?: $hero['image']; @endphp
            @if($mobileImagePath)
                <div class="relative w-[85%] max-w-[300px] mx-auto shrink-0">
                    {{-- Radial Glow Behind Image --}}
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-white/10 blur-[80px] z-0">
                    </div>

                    {{-- Portrait Image with Bottom Fade --}}
                    <img src="{{ str_starts_with($mobileImagePath, 'http') ? $mobileImagePath : asset('storage/' . $mobileImagePath) }}"
                        alt="{{ $hero['title'] }}"
                        class="relative z-10 w-full h-auto object-contain drop-shadow-2xl opacity-70 mix-blend-luminosity"
                        style="-webkit-mask-image: linear-gradient(to bottom, black 80%, transparent 100%); mask-image: linear-gradient(to bottom, black 80%, transparent 100%);">
                </div>
            @endif

        </div>

        {{-- ── Bottom Wave Contours (stroke-based, subtle) ── --}}
        <div class="absolute bottom-0 left-0 w-full z-0 pointer-events-none" style="height: 20%;">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                preserveAspectRatio="none">
                {{-- Wave line 1 --}}
                <path d="M0,224 C120,180 240,280 480,220 C720,160 960,280 1200,200 C1320,170 1380,210 1440,192"
                    fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" />
                {{-- Wave line 2 --}}
                <path d="M0,256 C180,210 360,300 600,240 C840,180 1020,290 1260,230 C1380,200 1420,240 1440,224"
                    fill="none" stroke="rgba(255,255,255,0.10)" stroke-width="1.2" />
                {{-- Wave line 3 --}}
                <path d="M0,288 C160,250 320,310 560,270 C800,230 1000,310 1240,260 C1360,240 1400,270 1440,256"
                    fill="none" stroke="rgba(255,255,255,0.07)" stroke-width="1" />
            </svg>
        </div>

        </div>

</section>