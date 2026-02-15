@extends('theme::layouts.admin')

@section('title', 'تعديل المحتوى: ' . $content->title)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">تعديل: {{ Str::limit($content->title, 40) }}</h1>
        <div class="flex gap-2">
            <a href="{{ $content->route }}" target="_blank"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">معاينة</a>
            <a href="{{ route('admin.vod.contents.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">العودة
                للقائمة</a>
        </div>
    </div>

    <form action="{{ route('admin.vod.contents.update', $content) }}" method="POST" enctype="multipart/form-data"
        id="vod-form">
        @csrf
        @method('PUT')

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Column -->
            <div class="w-full lg:w-2/3 space-y-6">

                <!-- Title, Slug, Embed -->
                <div class="bg-white p-6 rounded shadow">
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $content->title) }}"
                            class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                            placeholder="أدخل العنوان هنا" required>
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">الرابط الدائم (Slug)</label>
                        <div class="flex items-center">
                            <span
                                class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm"
                                dir="ltr">{{ config('app.url') }}/videos/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $content->slug) }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                                dir="ltr">
                        </div>
                    </div>

                    <!-- Embed Code -->
                    <div>
                        <label for="embed_code" class="block text-sm font-medium text-gray-700 mb-2">كود التضمين /
                            الرابط</label>
                        <textarea name="embed_code" id="embed_code" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent font-mono text-sm"
                            placeholder="<iframe...> or https://youtube.com/..."
                            dir="ltr">{{ old('embed_code', $content->embed_code) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">ضع كود التضمين (iframe) أو رابط الفيديو/الصوت.</p>
                        @error('embed_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="bg-white p-6 rounded shadow">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف / المحتوى</label>
                    <div class="@error('description') border border-red-500 rounded @enderror">
                        @php $value = old('description', $content->description); @endphp
                        @ckeditor('description')
                    </div>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="w-full lg:w-1/3 space-y-6">

                <!-- Type Box -->
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">النوع</h3>
                    <div class="space-y-2">
                        <label
                            class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="radio" name="type" value="video" {{ old('type', $content->type) == 'video' ? 'checked' : '' }} class="text-brand-accent h-4 w-4 focus:ring-brand-accent">
                            <span class="text-sm text-gray-700 select-none">فيديو (Video)</span>
                        </label>
                        <label
                            class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="radio" name="type" value="audio" {{ old('type', $content->type) == 'audio' ? 'checked' : '' }} class="text-brand-accent h-4 w-4 focus:ring-brand-accent">
                            <span class="text-sm text-gray-700 select-none">صوت (Audio)</span>
                        </label>
                    </div>
                </div>

                <!-- Thumbnail Box -->
                @include('theme::partials.thumbnail-field', [
                    'model' => $content,
                    'uploadField' => 'thumbnail',
                    'urlField' => 'thumbnail_url',
                    'label' => 'الصورة البارزة',
                    'currentImageUrl' => $content->thumbnail_path ? Storage::url($content->thumbnail_path) : null,
                    'currentImageRaw' => $content->thumbnail_path,
                ])

                <!-- Publish Box (Standardized) -->
                <x-admin.publish-card 
                    :publishedAt="$content->published_at" 
                    modelType="vod" 
                />
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @ckeditorScripts
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Image Preview Script
            window.previewImage = function (input) {
                const previewBox = document.getElementById('image-preview');
                const previewImg = previewBox.querySelector('img');
                const placeholder = document.getElementById('upload-placeholder');
                const currentImage = document.getElementById('current-image-preview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        previewBox.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                        if (currentImage) currentImage.classList.add('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
@endpush