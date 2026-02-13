<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Column (Content) -->
    <div class="w-full lg:w-2/3 space-y-6">
        
        <!-- Title -->
        <div class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الخاطرة</label>
                <input type="text" name="title" id="title" value="{{ old('title', $thought?->title) }}" 
                       class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                       placeholder="أدخل عنوان الخاطرة هنا">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Content Editor -->
        <div class="bg-white p-6 rounded shadow">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى الخاطرة</label>
            <div class="@error('content') border border-red-500 rounded @enderror">
                @php $value = old('content', $thought?->content ?? ''); @endphp
                @ckeditor('content')
            </div>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <!-- Sidebar Column (Metadata) -->
    <div class="w-full lg:w-1/3 space-y-6">
        
        <!-- Image Upload -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">الصورة</h3>
            <div class="space-y-3">
                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand-accent transition-colors cursor-pointer" onclick="document.getElementById('image').click()">
                    <div id="image-preview" class="{{ $thought?->image ? '' : 'hidden' }} mb-2">
                        <img src="{{ $thought?->image_url }}" alt="Preview" class="max-h-48 mx-auto rounded">
                    </div>
                    <div id="upload-placeholder" class="{{ $thought?->image ? 'hidden' : '' }}">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm text-gray-600">انقر لرفع صورة</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF (Max 3MB)</p>
                    </div>
                </div>
                <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">

                <div>
                    <label for="thumbnail_url" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-accent flex-shrink-0"></span>
                        أو رابط صورة خارجية
                    </label>
                    <input type="url" name="thumbnail_url" id="thumbnail_url" value="{{ old('thumbnail_url', $thought?->thumbnail_url) }}" 
                           class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-brand-accent"
                           placeholder="https://example.com/image.jpg"
                           dir="ltr">
                    <p class="mt-1 text-xs text-gray-500">
                        رابط صورة خارجية (يُستخدم إذا لم ترفع ملفًا)
                    </p>
                </div>
                
                @error('image')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
                @error('thumbnail_url')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Sort Order -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">ترتيب العرض</h3>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                <input type="number" name="sort_order" id="sort_order" 
                       value="{{ old('sort_order', $thought?->sort_order ?? 0) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('sort_order') border-red-500 @enderror"
                       min="0">
                <p class="mt-1 text-xs text-gray-500">الأرقام الأصغر تظهر أولاً</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Featured Toggle -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">خيارات إضافية</h3>
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" 
                       {{ old('is_featured', $thought?->is_featured) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-brand-accent focus:ring-brand-accent h-5 w-5">
                <span class="mr-3 text-sm font-medium text-gray-700">خاطرة مميزة</span>
            </label>
            <p class="mt-2 text-xs text-gray-500">الخواطر المميزة تظهر بشكل بارز في الصفحة الرئيسية</p>
        </div>

        <!-- Publish Card Component -->
        <x-admin.publish-card 
            :publishedAt="$thought?->published_at" 
            modelType="thought" 
        />

    </div>
</div>
