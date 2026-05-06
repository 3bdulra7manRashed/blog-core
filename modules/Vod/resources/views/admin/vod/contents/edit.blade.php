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

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-r-4 border-red-500 p-4 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-red-800">
                        توجد أخطاء في المدخلات
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.vod.contents.update', $content) }}" method="POST" enctype="multipart/form-data"
        id="vod-form" novalidate>
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
                            placeholder="أدخل العنوان هنا">
                        @error('title')
                            <div class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</div>
                        @enderror
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent font-mono text-sm @error('embed_code') border-red-500 @enderror"
                            placeholder="<iframe...> or https://youtube.com/..."
                            dir="ltr">{{ old('embed_code', $content->embed_code) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">ضع كود التضمين (iframe) أو رابط الفيديو/الصوت.</p>
                        @error('embed_code')
                            <div class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="bg-white p-6 rounded shadow">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف / المحتوى</label>
                    <div class="@error('description') border border-red-500 rounded @enderror">
                        @php $value = old('description', $content->description); @endphp
                        @ckeditor('description')
                    </div>
                    @error('description')
                        <div class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="w-full lg:w-1/3 space-y-6">

                <!-- Type Box -->
                @php
                    $videoEnabled = config('features.vod.video');
                    $audioEnabled = config('features.vod.audio');
                @endphp

                @if($videoEnabled || $audioEnabled)
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">النوع</h3>

                    @if($videoEnabled && !$audioEnabled)
                        <input type="hidden" name="type" value="{{ $content->type }}">
                        <div class="text-sm text-gray-600 bg-gray-50 p-2 rounded">فيديو (Video)</div>
                    @elseif($audioEnabled && !$videoEnabled)
                        <input type="hidden" name="type" value="{{ $content->type }}">
                        <div class="text-sm text-gray-600 bg-gray-50 p-2 rounded">صوت (Audio)</div>
                    @else
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                                <input type="radio" name="type" value="video" {{ old('type', $content->type) == 'video' ? 'checked' : '' }} class="text-brand-accent h-4 w-4 focus:ring-brand-accent">
                                <span class="text-sm text-gray-700 select-none">فيديو (Video)</span>
                            </label>
                            <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                                <input type="radio" name="type" value="audio" {{ old('type', $content->type) == 'audio' ? 'checked' : '' }} class="text-brand-accent h-4 w-4 focus:ring-brand-accent">
                                <span class="text-sm text-gray-700 select-none">صوت (Audio)</span>
                            </label>
                        </div>
                    @endif

                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Thumbnail Box -->
                @include('theme::partials.thumbnail-field', [
                    'model' => $content,
                    'uploadField' => 'thumbnail',
                    'urlField' => 'thumbnail_url',
                    'label' => 'الصورة البارزة',
                    'currentImageUrl' => $content->thumbnail_path ? Storage::url($content->thumbnail_path) : null,
                    'currentImageRaw' => $content->thumbnail_path,
                ])

                <!-- Playlists -->
                @if(config('features.vod.playlists') && isset($playlists) && $playlists->count() > 0)
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">قوائم التشغيل</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @php
                            $selectedPlaylists = old('playlists', $content->playlists->pluck('id')->toArray());
                        @endphp
                        @foreach($playlists as $playlist)
                            <label class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                                <input type="checkbox" name="playlists[]" value="{{ $playlist->id }}" {{ in_array($playlist->id, $selectedPlaylists) ? 'checked' : '' }} class="text-brand-accent h-4 w-4 rounded focus:ring-brand-accent border-gray-300">
                                <span class="text-sm text-gray-700 select-none">{{ $playlist->title }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('playlists')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

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