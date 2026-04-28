@extends('layouts.blog')

{{-- Khutab Homepage SEO --}}

@section('title', 'الخطب')

@section('description', 'تصفح مجموعة الخطب والمواعظ مع ' . config('branding.author.name'))

@section('keywords', 'خطب, مواعظ, دين, إسلام, ' . config('branding.default_keywords'))

@section('og_type', 'website')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-5xl">
    
    <header class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-[var(--brand-primary)] mb-4">الخطب</h1>
        <p class="text-gray-600 text-xl ">مجموعة من الخطب والمواعظ</p>
    </header>
    
    @if($posts->count() > 0)
        <div class="space-y-12">
            @foreach($posts as $post)
                <article class="border-b border-gray-100 pb-12 last:border-0">
                    @if($post->featured_image_url)
                        <a href="{{ route('khutab.show', $post->slug) }}" class="block mb-8">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full aspect-video md:aspect-auto md:h-[450px] object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        </a>
                    @endif
                    
                    <div class="text-center max-w-4xl mx-auto">
                        <h2 class="text-3xl md:text-4xl font-bold mb-6 leading-tight text-[var(--brand-primary)]">
                            <a href="{{ route('khutab.show', $post->slug) }}" class="hover:text-brand-accent transition-colors">
                                {{ $post->title }}
                            </a>
                        </h2>

                        @if($post->excerpt)
                            <p class="text-gray-600 mb-8 text-xl md:text-2xl leading-relaxed">{{ $post->excerpt }}</p>
                        @endif

                        @include('partials.share-buttons', ['post' => $post, 'route' => 'khutab.show'])
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-brand-muted text-lg">لا توجد خطب حالياً.</p>
        </div>
    @endif
</div>
@endsection
