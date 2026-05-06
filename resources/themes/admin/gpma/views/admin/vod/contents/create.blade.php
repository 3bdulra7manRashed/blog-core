@extends('theme::layouts.admin')

@section('title', 'رفع فيديو جديد')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">رفع فيديو جديد</h1>
    <a href="{{ route('admin.vod.contents.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        العودة للقائمة
    </a>
</div>

<form action="{{ route('admin.vod.contents.store') }}" method="POST" enctype="multipart/form-data" id="vod-form">
    @csrf
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Column (Content) -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Basic Info Card -->
            <div class="bg-white p-6 rounded shadow">
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                           placeholder="عنوان الفيديو" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Embed Code / Video URL -->
                <div class="mb-4">
                    <label for="embed_code" class="block text-sm font-medium text-gray-700 mb-2">رابط الفيديو / كود التضمين</label>
                    <input type="text" name="embed_code" id="embed_code" value="{{ old('embed_code') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('embed_code') border-red-500 @enderror"
                           dir="ltr" placeholder="https://youtube.com/..." required>
                    @error('embed_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white p-6 rounded shadow">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                <textarea name="description" id="description" rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Sidebar Column (Metadata) -->
        <div class="w-full lg:w-1/3 space-y-6" x-data="{ selectedContentType: '{{ old('type', 'video') }}' }">
            
            <!-- Thumbnail -->
            @include('theme::partials.thumbnail-field', [
                'model' => null,
                'uploadField' => 'thumbnail',
                'urlField' => 'thumbnail_url',
                'label' => 'الصورة المصغرة',
                'altField' => null, 
                'altHelpText' => null,
                'currentImageUrl' => null,
                'currentImageRaw' => null,
            ])

            <!-- Type -->
            @php
                $videoEnabled = config('features.vod.video');
                $audioEnabled = config('features.vod.audio');
            @endphp
    
            @if($videoEnabled || $audioEnabled)
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">نوع المحتوى</h3>
                
                @if($videoEnabled && !$audioEnabled)
                    <input type="hidden" name="type" value="video">
                    <div class="text-sm text-gray-600 bg-gray-50 p-2 rounded">فيديو (Video)</div>
                @endif

                @if($audioEnabled && !$videoEnabled)
                    <input type="hidden" name="type" value="audio">
                     <div class="text-sm text-gray-600 bg-gray-50 p-2 rounded">صوت (Audio)</div>
                @endif

                @if($videoEnabled && $audioEnabled)
                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 space-x-reverse cursor-pointer">
                            <input type="radio" name="type" value="video" x-model="selectedContentType" {{ old('type', 'video') == 'video' ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent">
                            <span class="text-sm text-gray-700">فيديو (Video)</span>
                        </label>
                        <label class="flex items-center space-x-2 space-x-reverse cursor-pointer">
                            <input type="radio" name="type" value="audio" x-model="selectedContentType" {{ old('type') == 'audio' ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent">
                            <span class="text-sm text-gray-700">صوت (Audio)</span>
                        </label>
                    </div>
                @endif

                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- Playlists -->
            @if(config('features.vod.playlists') && isset($playlists) && $playlists->count() > 0)
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">قوائم التشغيل</h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($playlists as $playlist)
                        <label x-show="selectedContentType === '{{ $playlist->type }}'" class="flex items-center space-x-2 space-x-reverse cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="playlists[]" value="{{ $playlist->id }}" {{ in_array($playlist->id, old('playlists', [])) ? 'checked' : '' }} class="text-brand-accent h-4 w-4 rounded focus:ring-brand-accent border-gray-300">
                            <span class="text-sm text-gray-700 select-none">{{ $playlist->title }}</span>
                        </label>
                    @endforeach
                </div>
                @error('playlists')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- Publishing Actions -->
            <x-admin.publish-card 
                :publishedAt="null" 
                modelType="vod" 
            />

        </div>
    </div>
</form>
@endsection

@push('scripts')
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
    });
</script>
@endpush