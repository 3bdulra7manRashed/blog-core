{{-- CTA Buttons — render only from settings, no fallbacks --}}
@if(!empty($cta['text']) && !empty($cta['link']))
    <div class="flex flex-wrap items-center justify-start mt-8 w-full gap-4">
        <a href="{{ $cta['link'] }}"
            class="px-8 py-3 bg-white text-[#0F766E] font-bold rounded-full transition-all duration-300 hover:-translate-y-1 hover:bg-gray-100 shadow-xl">
            {{ $cta['text'] }}
        </a>
    </div>
@endif