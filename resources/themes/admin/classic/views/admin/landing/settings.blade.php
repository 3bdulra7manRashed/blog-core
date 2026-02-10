@extends('theme::layouts.admin')

@section('title', 'إعدادات الصفحة الرئيسية')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">إعدادات الصفحة الرئيسية</h1>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
        {{ session('success') }}
    </div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
        <ul class="list-disc pr-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.landing.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Main Column --}}
        <div class="w-full lg:w-2/3 space-y-6">

            {{-- Hero Section --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">قسم الترحيب (Hero)</h2>

                {{-- Hero Title --}}
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

                {{-- Hero Subtitle --}}
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

                {{-- Hero Image Path --}}
                <div class="mb-4">
                    <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">مسار صورة الخلفية</label>
                    <input
                        type="text"
                        name="hero_image"
                        id="hero_image"
                        value="{{ old('hero_image', $settings->hero_image) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('hero_image') border-red-500 @enderror"
                        placeholder="landing/hero.jpg أو https://example.com/image.jpg"
                    >
                    @error('hero_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">مسار نسبي داخل storage أو رابط خارجي كامل</p>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">زر الدعوة للإجراء (CTA)</h2>

                {{-- CTA Text --}}
                <div class="mb-4">
                    <label for="cta_text" class="block text-sm font-medium text-gray-700 mb-1">نص الزر</label>
                    <input
                        type="text"
                        name="cta_text"
                        id="cta_text"
                        value="{{ old('cta_text', $settings->cta_text) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('cta_text') border-red-500 @enderror"
                        placeholder="تصفح المقالات"
                    >
                    @error('cta_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CTA Link --}}
                <div class="mb-4">
                    <label for="cta_link" class="block text-sm font-medium text-gray-700 mb-1">رابط الزر</label>
                    <input
                        type="text"
                        name="cta_link"
                        id="cta_link"
                        value="{{ old('cta_link', $settings->cta_link) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('cta_link') border-red-500 @enderror"
                        placeholder="https://example.com/posts"
                    >
                    @error('cta_link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Category Sections --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">أقسام التصنيفات</h2>

                {{-- Category One --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center mb-3">
                        <input
                            type="checkbox"
                            name="show_category_one"
                            id="show_category_one"
                            value="1"
                            {{ old('show_category_one', $settings->show_category_one) ? 'checked' : '' }}
                            class="h-4 w-4 text-brand-accent focus:ring-brand-accent border-gray-300 rounded"
                        >
                        <label for="show_category_one" class="mr-2 text-sm font-bold text-gray-700">إظهار القسم الأول</label>
                    </div>
                    <div>
                        <label for="category_one_id" class="block text-sm font-medium text-gray-700 mb-1">رقم التصنيف (ID)</label>
                        <input
                            type="number"
                            name="category_one_id"
                            id="category_one_id"
                            value="{{ old('category_one_id', $settings->category_one_id) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('category_one_id') border-red-500 @enderror"
                            placeholder="مثال: 1"
                            min="1"
                        >
                        @error('category_one_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Category Two --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center mb-3">
                        <input
                            type="checkbox"
                            name="show_category_two"
                            id="show_category_two"
                            value="1"
                            {{ old('show_category_two', $settings->show_category_two) ? 'checked' : '' }}
                            class="h-4 w-4 text-brand-accent focus:ring-brand-accent border-gray-300 rounded"
                        >
                        <label for="show_category_two" class="mr-2 text-sm font-bold text-gray-700">إظهار القسم الثاني</label>
                    </div>
                    <div>
                        <label for="category_two_id" class="block text-sm font-medium text-gray-700 mb-1">رقم التصنيف (ID)</label>
                        <input
                            type="number"
                            name="category_two_id"
                            id="category_two_id"
                            value="{{ old('category_two_id', $settings->category_two_id) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('category_two_id') border-red-500 @enderror"
                            placeholder="مثال: 2"
                            min="1"
                        >
                        @error('category_two_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Khutab Category --}}
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center mb-3">
                        <input
                            type="checkbox"
                            name="show_khutab"
                            id="show_khutab"
                            value="1"
                            {{ old('show_khutab', $settings->show_khutab) ? 'checked' : '' }}
                            class="h-4 w-4 text-brand-accent focus:ring-brand-accent border-gray-300 rounded"
                        >
                        <label for="show_khutab" class="mr-2 text-sm font-bold text-gray-700">إظهار قسم الخطب</label>
                    </div>
                    <div>
                        <label for="khutab_category_id" class="block text-sm font-medium text-gray-700 mb-1">رقم تصنيف الخطب (ID)</label>
                        <input
                            type="number"
                            name="khutab_category_id"
                            id="khutab_category_id"
                            value="{{ old('khutab_category_id', $settings->khutab_category_id) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brand-accent focus:border-brand-accent @error('khutab_category_id') border-red-500 @enderror"
                            placeholder="مثال: 3"
                            min="1"
                        >
                        @error('khutab_category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="w-full lg:w-1/3 space-y-6">

            {{-- Display Toggles --}}
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-4">خيارات العرض</h3>

                <div class="space-y-3">
                    {{-- Show Quotes Section --}}
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

                    {{-- Show Thoughts --}}
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="show_thoughts"
                            id="show_thoughts"
                            value="1"
                            {{ old('show_thoughts', $settings->show_thoughts) ? 'checked' : '' }}
                            class="h-4 w-4 text-brand-accent focus:ring-brand-accent border-gray-300 rounded"
                        >
                        <label for="show_thoughts" class="mr-2 text-sm font-medium text-gray-700">
                            إظهار قسم الخواطر
                        </label>
                    </div>

                    {{-- Show Releases --}}
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="show_releases"
                            id="show_releases"
                            value="1"
                            {{ old('show_releases', $settings->show_releases) ? 'checked' : '' }}
                            class="h-4 w-4 text-brand-accent focus:ring-brand-accent border-gray-300 rounded"
                        >
                        <label for="show_releases" class="mr-2 text-sm font-medium text-gray-700">
                            إظهار قسم الإصدارات
                        </label>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="bg-white p-4 rounded-lg shadow">
                <button
                    type="submit"
                    class="w-full px-6 py-3 bg-brand-accent text-white rounded-lg hover:bg-amber-600 transition-colors font-medium"
                >
                    حفظ الإعدادات
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
