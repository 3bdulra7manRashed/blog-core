{{-- Reusable Post Card Component for Landing Page --}}
<article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
    {{-- Featured Image --}}
    @if($post->featured_image_url)
        <a href="{{ route('post.show', $post->slug) }}" class="block aspect-video overflow-hidden">
            <img
                src="{{ $post->featured_image_url }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            >
        </a>
    @else
        <a href="{{ route('post.show', $post->slug) }}" class="block aspect-video bg-gradient-to-br from-brand-secondary/20 to-brand-accent/20 flex items-center justify-center">
            <svg class="w-12 h-12 text-brand-accent/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </a>
    @endif

    {{-- Content --}}
    <div class="p-5 text-right">
        {{-- Categories --}}
        @if($post->categories->count() > 0)
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($post->categories->take(2) as $category)
                    <span class="text-xs font-medium text-brand-accent bg-brand-accent/10 px-2 py-1 rounded">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <h3 class="text-xl font-serif font-bold text-brand-primary mb-3 line-clamp-2 group-hover:text-brand-accent transition-colors">
            <a href="{{ route('post.show', $post->slug) }}">
                {{ $post->title }}
            </a>
        </h3>

        @if($post->excerpt)
            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                {{ $post->excerpt }}
            </p>
        @endif

        <div class="flex items-center justify-between text-sm text-gray-500 pt-3 border-t border-gray-100">
            <span>{{ $post->published_at->format('Y/m/d') }}</span>
            <a href="{{ route('post.show', $post->slug) }}" class="text-brand-accent hover:text-brand-primary font-medium transition-colors">
                اقرأ المزيد ←
            </a>
        </div>
    </div>
</article>
