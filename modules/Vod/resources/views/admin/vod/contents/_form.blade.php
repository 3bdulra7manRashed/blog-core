<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Column (Content) -->
    <div class="w-full lg:w-2/3 space-y-6">
        
        <!-- Title & Slug -->
        <div class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                <input type="text" name="title" id="title" value="{{ old('title', $content->title ?? '') }}" 
                       class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                       placeholder="أدخل عنوان الفيديو/الصوت هنا" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Embed Code (Inserted Here) -->
            <div class="mb-4">
                <label for="embed_code" class="block text-sm font-medium text-gray-700 mb-2">كود التضمين / الرابط</label>
                <textarea name="embed_code" id="embed_code" rows="3"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent font-mono text-sm @error('embed_code') border-red-500 @enderror"
                       placeholder="<iframe...> or https://..." dir="ltr">{{ old('embed_code', $content->embed_code ?? '') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">ضع كود التضمين (iframe) أو الرابط المباشر.</p>
                @error('embed_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">الرابط الدائم (Slug)</label>
                <div class="flex items-center">
                    <span class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm" dir="ltr">{{ config('app.url') }}/videos/</span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $content->slug ?? '') }}" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('slug') border-red-500 @enderror text-sm"
                           dir="ltr">
                </div>
                <p class="mt-1 text-xs text-gray-500">يترك فارغاً للتوليد التلقائي.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description Editor -->
        <div class="bg-white p-6 rounded shadow">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف / المحتوى</label>
            <div class="@error('description') border border-red-500 rounded @enderror">
                <!-- Using standard Blade directive if available, else standard textarea with class -->
                {{-- @ckeditor('description') --}}
                <textarea name="description" id="description" class="ckeditor">{{ old('description', $content->description ?? '') }}</textarea>
            </div>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <!-- Sidebar Column (Metadata) -->
    <div class="w-full lg:w-1/3 space-y-6">
        
        <!-- Publish Box -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">النشر</h3>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent">
                    <option value="draft" {{ old('status', $content->status ?? '') == 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="published" {{ old('status', $content->status ?? '') == 'published' ? 'selected' : '' }}>منشور</option>
                    <option value="archived" {{ old('status', $content->status ?? '') == 'archived' ? 'selected' : '' }}>مؤرشف</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النشر</label>
                <input type="datetime-local" name="published_at" id="published_at" 
                       value="{{ old('published_at', isset($content->published_at) ? $content->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                       dir="ltr">
            </div>

            <div class="pt-4 border-t mt-4">
                <button type="submit" class="w-full px-6 py-2 bg-brand-primary text-white rounded hover:bg-opacity-90 transition-colors text-sm font-medium shadow-sm">
                    {{ isset($content) ? 'تحديث' : 'نشر' }}
                </button>
            </div>
        </div>

        <!-- Format Box (Replaces Categories) -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">النوع</h3>
            <div class="space-y-2">
                <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                    <input type="radio" name="type" value="video" 
                           {{ old('type', $content->type ?? 'video') == 'video' ? 'checked' : '' }}
                           class="rounded-full border-gray-300 text-brand-accent focus:ring-brand-accent h-4 w-4">
                    <span class="text-sm text-gray-700 select-none">فيديو</span>
                </label>
                <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                    <input type="radio" name="type" value="audio" 
                           {{ old('type', $content->type ?? '') == 'audio' ? 'checked' : '' }}
                           class="rounded-full border-gray-300 text-brand-accent focus:ring-brand-accent h-4 w-4">
                    <span class="text-sm text-gray-700 select-none">صوت</span>
                </label>
            </div>
        </div>

        <!-- Thumbnail Box (Copied from Featured Image) -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">الصورة البارزة</h3>
            <div class="space-y-3">
                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand-accent transition-colors cursor-pointer" onclick="document.getElementById('thumbnail').click()">
                    
                    <!-- Preview Logic -->
                    <div id="image-preview" class="{{ (isset($content) && $content->thumbnail_path) ? '' : 'hidden' }} mb-2">
                        <img src="{{ (isset($content) && $content->thumbnail_path) ? Storage::url($content->thumbnail_path) : '' }}" 
                             alt="Preview" class="max-h-48 mx-auto rounded">
                    </div>
                    
                    <!-- Placeholder logic -->
                    <div id="upload-placeholder" class="{{ (isset($content) && $content->thumbnail_path) ? 'hidden' : '' }}">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm text-gray-600">انقر لرفع صورة</p>
                        <p class="text-xs text-gray-500">PNG, JPG (Max 2MB)</p>
                    </div>
                </div>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden" onchange="previewImage(this)">
                
                @error('thumbnail')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

    </div>
</div>
