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
    
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">عنوان القائمة</label>
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
        </div>

        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
             <a href="{{ route('admin.vod.playlists.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">إلغاء</a>
            <button type="submit" class="px-6 py-2 bg-[#0F766E] text-white rounded-md hover:bg-[#0d9488] transition-colors duration-200 font-medium">
                حفظ
            </button>
        </div>
    </div>
</form>
@endsection
