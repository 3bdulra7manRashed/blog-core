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
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">صورة الغلاف</h3>
            <div class="space-y-3">
                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand-accent transition-colors cursor-pointer" onclick="document.getElementById('cover_file').click()">
                    <div id="image-preview" class="{{ $book?->cover_image ? '' : 'hidden' }} mb-2">
                        <img src="{{ $book?->cover_image ?? '' }}" alt="Preview" class="max-h-48 mx-auto rounded">
                    </div>
                    <div id="upload-placeholder" class="{{ $book?->cover_image ? 'hidden' : '' }}">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm text-gray-600">انقر لرفع صورة</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF (Max 3MB)</p>
                    </div>
                </div>
                <input type="file" name="cover_file" id="cover_file" accept="image/*" class="hidden" onchange="previewCoverImage(this)">
                
                <div>
                    <label for="cover_url" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-accent flex-shrink-0"></span>
                        أو رابط صورة
                    </label>
                    <input type="text" name="cover_url" id="cover_url" value="{{ old('cover_url', $book?->cover_image) }}" 
                           class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-brand-accent"
                           placeholder="https://example.com/image.jpg"
                           dir="ltr">
                    <p class="mt-1 text-xs text-gray-500">
                        رابط صورة خارجية (يُستخدم إذا لم ترفع ملفًا)
                    </p>
                </div>
                
                @error('cover_file')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
                @error('cover_url')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- External URL (Buy Link) -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">رابط الشراء</h3>
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
    window.previewCoverImage = function(input) {
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
