{{-- Release (Book) Card --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 group text-right">
    @if($book->cover_image)
        <div class="aspect-[3/4] overflow-hidden">
            <img
                src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset('storage/' . $book->cover_image) }}"
                alt="{{ $book->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            >
        </div>
    @endif
    <div class="p-5">
        <h3 class="text-lg font-serif font-bold text-brand-primary mb-2 group-hover:text-brand-accent transition-colors">
            {{ $book->title }}
        </h3>
        @if($book->excerpt)
            <p class="text-gray-600 text-sm line-clamp-2">{{ $book->excerpt }}</p>
        @endif
    </div>
</div>
