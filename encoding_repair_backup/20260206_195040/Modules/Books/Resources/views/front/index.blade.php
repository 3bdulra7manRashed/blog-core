@extends('layouts.blog')

@section('title', 'الإصدارات')

@section('content')
<div class="container mx-auto px-4 max-w-5xl py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">الإصدارات</h1>
        <p class="text-gray-600 mt-2">مجموعة من الكتب والإصدارات المنشورة</p>
    </div>

    @if($books->count() > 0)
        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
                <article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <!-- Cover Image -->
                    @if($book->cover_image)
                        <a href="{{ route('books.show', $book->slug) }}" class="block aspect-[3/4] overflow-hidden bg-gray-100">
                            <img src="{{ $book->cover_image }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                    @else
                        <a href="{{ route('books.show', $book->slug) }}" class="block aspect-[3/4] bg-gradient-to-br from-brand-secondary to-brand-primary flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </a>
                    @endif

                    <!-- Content -->
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-brand-primary mb-2 line-clamp-2">
                            <a href="{{ route('books.show', $book->slug) }}" class="hover:text-brand-accent transition-colors">
                                {{ $book->title }}
                            </a>
                        </h2>
                        
                        @if($book->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ Str::limit($book->excerpt, 120) }}
                            </p>
                        @endif

                        <a href="{{ route('books.show', $book->slug) }}" 
                           class="inline-flex items-center text-sm font-medium text-brand-accent hover:text-brand-primary transition-colors">
                            عرض الكتاب
                            <svg class="w-4 h-4 mr-1 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="mt-8" dir="ltr">
                {{ $books->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-gray-50 rounded-lg">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-600 mb-2">لا توجد إصدارات حالياً</h3>
            <p class="text-gray-500">سيتم إضافة الكتب قريباً</p>
        </div>
    @endif
</div>
@endsection
