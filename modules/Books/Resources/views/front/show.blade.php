@extends('layouts.blog')

@section('title', $book->title)

@section('content')
    <div class="container mx-auto px-4 max-w-5xl py-8">
        <!-- Book Header -->
        <div class="flex flex-col md:flex-row gap-8 mb-8">
            <!-- Cover Image -->
            <div class="w-full md:w-1/3 flex-shrink-0">
                @if($book->cover_image)
                    <div class="aspect-[3/4] rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div
                        class="aspect-[3/4] rounded-lg bg-gradient-to-br from-brand-secondary to-brand-primary flex items-center justify-center shadow-lg">
                        <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Book Info -->
            <div class="flex-1">
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-brand-primary mb-4">
                    {{ $book->title }}
                </h1>

                @if($book->excerpt)
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        {{ $book->excerpt }}
                    </p>
                @endif

                <!-- CTA Button -->
                <div class="mt-6">
                    <a href="{{ $book->external_url }}" target="_blank" rel="nofollow sponsored noopener"
                        class="inline-flex items-center px-8 py-3 bg-brand-accent text-white font-bold rounded-lg hover:bg-amber-700 transition-colors shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        احصل عليه الآن
                    </a>
                </div>

                @if($book->published_at)
                    <p class="text-sm text-gray-500 mt-4">
                        تاريخ النشر: {{ $book->published_at->format('Y/m/d') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($book->description)
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-xl font-bold text-brand-primary mb-4">نبذة عن الكتاب</h2>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($book->description)) !!}
                </div>
            </div>
        @endif

        <!-- Back Link -->
        <div class="mt-12 pt-6 border-t border-gray-200">
            <a href="{{ route('books.index') }}"
                class="inline-flex items-center text-brand-accent hover:text-brand-primary transition-colors">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
                العودة للإصدارات
            </a>
        </div>
    </div>
@endsection