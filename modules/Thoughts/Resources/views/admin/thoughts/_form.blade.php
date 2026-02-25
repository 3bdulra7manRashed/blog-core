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
        @include('theme::partials.thumbnail-field', [
            'model' => $thought ?? null,
            'uploadField' => 'image',
            'urlField' => 'thumbnail_url',
            'label' => 'الصورة',
            'currentImageUrl' => $thought?->image_url,
            'currentImageRaw' => $thought?->image,
        ])

        <!-- Sort Order -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">ترتيب العرض</h3>
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
            <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">خيارات إضافية</h3>
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
