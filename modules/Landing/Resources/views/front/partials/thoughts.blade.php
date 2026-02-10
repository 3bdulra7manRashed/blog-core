{{-- Thoughts Section (Stories-style horizontal scroll) --}}
<section class="py-10">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex gap-6 overflow-x-auto pb-4">
            @foreach($thoughts as $thought)
                <div class="flex flex-col items-center w-24 shrink-0">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-brand-primary">
                        <img src="{{ $thought->image ? asset('storage/'.$thought->image) : asset('images/default-avatar.png') }}"
                             alt="{{ $thought->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs mt-2 text-center line-clamp-2">
                        {{ $thought->title }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>
