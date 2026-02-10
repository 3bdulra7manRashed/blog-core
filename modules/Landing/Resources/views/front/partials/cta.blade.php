{{-- CTA Buttons — render only from settings, no fallbacks --}}
@if(!empty($cta['text']) && !empty($cta['link']))
<div class="flex flex-wrap items-center justify-center gap-4">
    <a href="{{ $cta['link'] }}" class="px-8 py-3 bg-white text-brand-primary font-bold rounded-full hover:bg-gray-100 transition-colors shadow-lg">
        {{ $cta['text'] }}
    </a>
</div>
@endif
