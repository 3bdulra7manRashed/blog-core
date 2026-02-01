@extends('layouts.admin')

@section('title', 'إضافة فيديو/صوت جديد')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">إضافة محتوى جديد</h1>
    <a href="{{ route('admin.vod.contents.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
        العودة للقائمة
    </a>
</div>

<form action="{{ route('admin.vod.contents.store') }}" method="POST" enctype="multipart/form-data" id="vod-form">
    @csrf
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Column -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Title, Slug, Embed -->
            <div class="bg-white p-6 rounded shadow">
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                           placeholder="أدخل العنوان هنا" required>
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">الرابط الدائم (Slug)</label>
                    <div class="flex items-center">
                        <span class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm" dir="ltr">{{ config('app.url') }}/videos/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                               dir="ltr">
                    </div>
                </div>

                <!-- Embed Code -->
                <div>
                     <label for="embed_code" class="block text-sm font-medium text-gray-700 mb-2">كود التضمين / الرابط</label>
                     <textarea name="embed_code" id="embed_code" rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent font-mono text-sm"
                            placeholder="<iframe...> or https://youtube.com/..." dir="ltr">{{ old('embed_code') }}</textarea>
                     <p class="mt-1 text-xs text-gray-500">ضع كود التضمين (iframe) أو رابط الفيديو/الصوت.</p>
                     @error('embed_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Content Editor -->
            <div class="bg-white p-6 rounded shadow">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف / المحتوى</label>
                <div class="@error('description') border border-red-500 rounded @enderror">
                    @php $value = old('description'); @endphp
                    @ckeditor('description')
                </div>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Publish Box (Strict Clone of Posts) -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">النشر</h3>
                
                <div class="mb-4">
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النشر</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                           dir="ltr">
                </div>
                
                <!-- Hidden Status Input -->
                <input type="hidden" name="status" id="status_input" value="{{ old('status', 'published') }}">

                <div class="flex items-center justify-between pt-4 border-t mt-4">
                    <button type="submit" onclick="document.getElementById('status_input').value='draft'" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                        حفظ كمسودة
                    </button>
                    <button type="submit" onclick="document.getElementById('status_input').value='published'" 
                            class="px-6 py-2 bg-brand-primary text-white rounded hover:bg-opacity-90 transition-colors text-sm font-medium shadow-sm">
                        نشر الآن
                    </button>
                </div>
            </div>

            <!-- Type Box -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">النوع</h3>
                <div class="space-y-2">
                    <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                        <input type="radio" name="type" value="video" checked class="text-brand-accent h-4 w-4 focus:ring-brand-accent">
                        <span class="text-sm text-gray-700 select-none">فيديو (Video)</span>
                    </label>
                    <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                        <input type="radio" name="type" value="audio" class="text-brand-accent h-4 w-4 focus:ring-brand-accent">
                        <span class="text-sm text-gray-700 select-none">صوت (Audio)</span>
                    </label>
                </div>
            </div>

            <!-- Thumbnail Box -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">الصورة البارزة</h3>
                <div class="space-y-3">
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand-accent transition-colors cursor-pointer" onclick="document.getElementById('thumbnail').click()">
                        <div id="image-preview" class="hidden mb-2"><img src="" class="max-h-48 mx-auto rounded"></div>
                        <div id="upload-placeholder">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <p class="mt-1 text-sm text-gray-600">انقر لرفع صورة</p>
                            <p class="text-xs text-gray-500">PNG, JPG (Max 2MB)</p>
                        </div>
                    </div>
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@ckeditorScripts
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image Preview Script
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
    
        // Auto-Slug Generator
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const form = document.getElementById('vod-form');
        
        if (titleInput && slugInput) {
            titleInput.addEventListener('blur', function() {
                if (slugInput.value.trim() === '') {
                    generateSlug();
                }
            });
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!slugInput.value.trim() && titleInput.value.trim()) {
                        generateSlug();
                    }
                });
            }

            function generateSlug() {
                const title = titleInput.value;
                const slug = title.trim()
                    .replace(/\s+/g, '-')           
                    .replace(/[^\w\u0600-\u06FF\-]+/g, '') 
                    .replace(/\-\-+/g, '-')         
                    .replace(/^-+/, '')             
                    .replace(/-+$/, '');            
                
                slugInput.value = slug;
            }
        }
    });
</script>
@endpush
