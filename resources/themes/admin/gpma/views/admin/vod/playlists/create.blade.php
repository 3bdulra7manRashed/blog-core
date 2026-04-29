@extends('theme::layouts.admin')

@section('title', 'إنشاء قائمة جديدة')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">إنشاء قائمة جديدة</h1>
    <a href="{{ route('admin.vod.playlists.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        إلغاء
    </a>
</div>

<form action="{{ route('admin.vod.playlists.store') }}" method="POST" class="max-w-4xl">
    @csrf
    
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في البيانات المدخلة:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 space-y-6">
            <!-- Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">نوع القائمة</label>
                <div class="relative">
                    <select name="type" id="type" 
                            class="w-full bg-none appearance-none text-right pr-4 pl-10 py-2 border border-gray-300 rounded-md bg-white focus:ring-brand-accent focus:border-brand-accent transition-colors @error('type') border-red-500 @enderror" 
                            required>
                        <option value="video" {{ old('type', 'video') == 'video' ? 'selected' : '' }}>فيديو</option>
                        @if(feature('vod.audio'))
                            <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>صوت</option>
                        @endif
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">عنوان القائمة</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent @error('title') border-red-500 @enderror">
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
        </div>

        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
             <a href="{{ route('admin.vod.playlists.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">إلغاء</a>
            <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded-md hover:bg-opacity-90 transition-colors font-medium">
                حفظ
            </button>
        </div>
    </div>
</form>
@endsection
