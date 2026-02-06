@php
    $book = $book ?? null;
@endphp

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Column -->
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

    <!-- Sidebar Column -->
    <div class="w-full lg:w-1/3 space-y-6">
        
        <!-- Publishing Actions -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">النشر</h3>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm">
                    <option value="draft" {{ old('status', $book?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="published" {{ old('status', $book?->status) === 'published' ? 'selected' : '' }}>منشور</option>
                    <option value="archived" {{ old('status', $book?->status) === 'archived' ? 'selected' : '' }}>مؤرشف</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النشر</label>
                <input type="datetime-local" name="published_at" id="published_at" 
                       value="{{ old('published_at', $book?->published_at?->format('Y-m-d\TH:i')) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                       dir="ltr">
            </div>

            <div class="flex items-center justify-end pt-4 border-t mt-4">
                <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded hover:bg-opacity-90 transition-colors text-sm font-medium shadow-sm">
                    {{ $book ? 'تحديث' : 'حفظ' }}
                </button>
            </div>
        </div>

        <!-- External URL -->
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

        <!-- Cover Image -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">صورة الغلاف</h3>
            <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">رابط الصورة</label>
                <input type="text" name="cover_image" id="cover_image" value="{{ old('cover_image', $book?->cover_image) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm @error('cover_image') border-red-500 @enderror"
                       placeholder="/images/books/cover.jpg"
                       dir="ltr">
                <p class="mt-1 text-xs text-gray-500">رابط صورة غلاف الكتاب</p>
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            @if($book?->cover_image)
                <div class="mt-3">
                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-48 object-cover rounded">
                </div>
            @endif
        </div>

    </div>
</div>
