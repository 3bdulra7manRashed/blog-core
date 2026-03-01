@extends('theme::layouts.admin')

@section('title', 'إعدادات الصفحة الرئيسية')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">إعدادات الصفحة الرئيسية</h1>
</div>

<form action="{{ route('admin.settings.landing.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Column (Content) -->
        <div class="w-full lg:w-2/3 space-y-6">
            <!-- Hero Section Settings -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-[#1F3A6E] mb-4 pb-2 border-b">قسم الترحيب (Hero)</h2>
                
                <!-- Hero Title -->
                <div class="mb-4">
                    <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-1">عنوان الصفحة الرئيسية</label>
                    <input 
                        type="text" 
                        name="hero_title" 
                        id="hero_title" 
                        value="{{ old('hero_title', $settings->hero_title) }}" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('hero_title') border-red-500 @enderror"
                        placeholder="مرحباً بكم في موقعي"
                    >
                    @error('hero_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hero Subtitle -->
                <div class="mb-4">
                    <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1">العنوان الفرعي</label>
                    <textarea 
                        name="hero_subtitle" 
                        id="hero_subtitle" 
                        rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('hero_subtitle') border-red-500 @enderror"
                        placeholder="نص تعريفي قصير يظهر أسفل العنوان الرئيسي..."
                    >{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                    @error('hero_subtitle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="w-full lg:w-1/3 space-y-6">
            <!-- Hero Image -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-bold text-[#1F3A6E] mb-4">صورة الخلفية</h3>
                
                <!-- Current Image Preview -->
                @if($settings->hero_image)
                    <div class="mb-4">
                        <img 
                            src="{{ str_starts_with($settings->hero_image, 'http') ? $settings->hero_image : asset('storage/' . $settings->hero_image) }}" 
                            alt="صورة الخلفية الحالية" 
                            class="w-full h-40 object-cover rounded-lg border"
                        >
                        <p class="text-sm text-gray-500 mt-1">الصورة الحالية</p>
                    </div>
                @endif

                <!-- Upload New Image -->
                <div class="mb-4">
                    <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">رفع صورة جديدة</label>
                    <input 
                        type="file" 
                        name="hero_image" 
                        id="hero_image" 
                        accept="image/*"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm @error('hero_image') border-red-500 @enderror"
                    >
                    @error('hero_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 5 ميجابايت | الصيغ: JPEG, PNG, GIF, WebP</p>
                </div>

                <!-- Or Use URL -->
                <div class="mb-4">
                    <label for="hero_image_url" class="block text-sm font-medium text-gray-700 mb-1">أو رابط صورة خارجية</label>
                    <input 
                        type="text" 
                        name="hero_image_url" 
                        id="hero_image_url" 
                        placeholder="https://example.com/image.jpg"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm @error('hero_image_url') border-red-500 @enderror"
                    >
                    @error('hero_image_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Display Options -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-bold text-[#1F3A6E] mb-4">خيارات العرض</h3>
                
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="show_quotes_section" 
                        id="show_quotes_section" 
                        value="1"
                        {{ old('show_quotes_section', $settings->show_quotes_section) ? 'checked' : '' }}
                        class="h-4 w-4 text-brand-accent focus:ring-brand-accent border-gray-300 rounded"
                    >
                    <label for="show_quotes_section" class="mr-2 text-sm font-medium text-gray-700">
                        إظهار قسم أحدث المقالات
                    </label>
                </div>
            </div>

            <!-- Save Button -->
            <div class="bg-white p-4 rounded-lg shadow">
                <button 
                    type="submit" 
                    class="w-full px-6 py-3 bg-brand-primary text-white rounded-lg hover:bg-[#0d9488] transition-colors duration-200 font-medium"
                >
                    حفظ الإعدادات
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
