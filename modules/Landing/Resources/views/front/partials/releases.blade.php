{{-- Releases Section --}}
<section class="py-16">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-brand-primary mb-4">أحدث الإصدارات</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">أحدث الكتب والإصدارات المتاحة</p>
        </div>

        {{-- Releases Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($releases as $book)
                @include('landing::front.partials.release-card', ['book' => $book])
            @endforeach
        </div>

    </div>
</section>