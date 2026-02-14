@extends('theme::layouts.admin')

@section('title', 'رفع فيديو جديد')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">رفع فيديو جديد</h1>
        <a href="{{ route('admin.vod.contents.index') }}"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
            إلغاء
        </a>
    </div>

    <form action="{{ route('admin.vod.contents.store') }}" method="POST" class="max-w-4xl">
        @csrf

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent @error('title') border-red-500 @enderror"
                        required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                @php
                    $videoEnabled = config('features.vod.video');
                    $audioEnabled = config('features.vod.audio');
                @endphp
        
                @if($videoEnabled || $audioEnabled)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع المحتوى</label>

                    {{-- Only Video Enabled --}}
                    @if($videoEnabled && !$audioEnabled)
                        <input type="hidden" name="type" value="video">
                        <div class="text-sm text-gray-600 px-4 py-2 bg-gray-50 rounded-md border border-gray-200">
                            فيديو (Video)
                        </div>
                    @endif

                    {{-- Only Audio Enabled --}}
                    @if($audioEnabled && !$videoEnabled)
                        <input type="hidden" name="type" value="audio">
                        <div class="text-sm text-gray-600 px-4 py-2 bg-gray-50 rounded-md border border-gray-200">
                            صوت (Audio)
                        </div>
                    @endif

                    {{-- Both Enabled --}}
                    @if($videoEnabled && $audioEnabled)
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="video"
                                    {{ old('type', 'video') == 'video' ? 'checked' : '' }}
                                    class="text-brand-accent focus:ring-brand-accent">
                                <span class="text-sm text-gray-700">فيديو (Video)</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="audio"
                                    {{ old('type') == 'audio' ? 'checked' : '' }}
                                    class="text-brand-accent focus:ring-brand-accent">
                                <span class="text-sm text-gray-700">صوت (Audio)</span>
                            </label>
                        </div>
                    @endif

                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Video URL -->
                <div>
                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">رابط الفيديو</label>
                    <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent @error('video_url') border-red-500 @enderror"
                        dir="ltr" placeholder="https://youtube.com/...">
                    @error('video_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.vod.contents.index') }}"
                    class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">إلغاء</a>
                <button type="submit"
                    class="px-6 py-2 bg-brand-primary text-white rounded-md hover:bg-opacity-90 transition-colors font-medium">
                    حفظ
                </button>
            </div>
        </div>
    </form>
@endsection