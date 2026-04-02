{{-- Admin Primary Action Button (Create / Add New) --}}
<a {{ $attributes->merge(['class' => 'inline-flex items-center px-5 py-2.5 text-lg bg-brand-accent text-white hover:bg-teal-700 hover:text-white rounded-md hover:bg-opacity-90 transition-colors shadow-sm hover:shadow-md']) }}>
    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
    {{ $slot }}
</a>
