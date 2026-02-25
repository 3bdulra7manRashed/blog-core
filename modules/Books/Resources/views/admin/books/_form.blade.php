@php
    $book = $book ?? null;
@endphp

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Column (Content) -->
    <div class="w-full lg:w-2/3 space-y-6">
        
        <!-- Title & Slug -->
        <div class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الكتاب</label>
                <input type="text" name="title" id="title" value="{{ old('title', $book?->title) }}" 
                       class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                       placeholder="أدخل عنوان الكتاب هنا">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">الرابط الدائم (Slug)</label>
                <div class="flex items-center">
                    <span class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm" dir="ltr">{{ config('app.url') }}/books/</span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $book?->slug) }}" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('slug') border-red-500 @enderror text-sm"
                           dir="ltr">
                </div>
                <p class="mt-1 text-xs text-gray-500">سيتم توليده تلقائيًا من العنوان إذا ترك فارغًا.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Excerpt -->
        <div class="bg-white p-6 rounded shadow">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">المقتطف</label>
            <textarea name="excerpt" id="excerpt" rows="3" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('excerpt') border-red-500 @enderror"
                      placeholder="وصف مختصر للكتاب يظهر في القوائم">{{ old('excerpt', $book?->excerpt) }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="bg-white p-6 rounded shadow">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف الكامل</label>
            <textarea name="description" id="description" rows="8" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('description') border-red-500 @enderror"
                      placeholder="وصف تفصيلي للكتاب">{{ old('description', $book?->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <!-- Sidebar Column (Metadata) -->
    <div class="w-full lg:w-1/3 space-y-6">
        
        <!-- Cover Image (Posts-style drag & drop) -->
        @include('theme::partials.thumbnail-field', [
            'model' => $book ?? null,
            'uploadField' => 'cover_file',
            'urlField' => 'cover_url',
            'label' => 'صورة الغلاف',
            'currentImageUrl' => $book?->cover_image,
            'currentImageRaw' => $book?->cover_image,
        ])

        <!-- External URL (Buy Link) -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">رابط الشراء</h3>
            <div>
                <label for="external_url" class="block text-sm font-medium text-gray-700 mb-2">الرابط الخارجي</label>
                <input type="url" name="external_url" id="external_url" value="{{ old('external_url', $book?->external_url) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm @error('external_url') border-red-500 @enderror"
                       placeholder="https://example.com/buy-book"
                       dir="ltr">
                <p class="mt-1 text-xs text-gray-500">رابط صفحة شراء الكتاب (أمازون، جرير، إلخ)</p>
                @error('external_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Publishing Actions (at bottom for better UX) -->
        <x-admin.publish-card 
            :publishedAt="$book->published_at ?? null" 
            modelType="book" 
        />

    </div>
</div>

@push('scripts')
<script>
    // Cover Image Preview Script
    window.previewImage = function(input) {
        const previewBox = document.getElementById('image-preview');
        const previewImg = previewBox.querySelector('img');
        const placeholder = document.getElementById('upload-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
