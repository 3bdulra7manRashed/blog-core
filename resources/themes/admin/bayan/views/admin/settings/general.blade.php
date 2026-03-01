@extends('theme::layouts.admin')

@section('title', 'الإعدادات العامة')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">الإعدادات العامة</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-r-4 border-green-500 p-4 rounded shadow-sm relative">
            <div class="flex items-center">
                <svg class="w-5 h-5 ml-2 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="font-medium text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg shadow-sm">
            <ul class="list-disc pr-5 space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Form Settings -->
            <div class="w-full lg:w-2/3 space-y-6">

                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-xl font-bold border-b pb-4 mb-6">المعلومات الأساسية</h2>

                    <!-- Site Name -->
                    <div class="mb-6">
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الموقع</label>
                        <input type="text" name="site_name" id="site_name"
                            value="{{ old('site_name', setting('site_name', config('branding.site_name'))) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('site_name') border-red-500 @enderror"
                            placeholder="أدخل اسم الموقع الذي سيظهر في الشريط العلوي">
                        @error('site_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Site Logo -->
                    <div>
                        <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">شعار الموقع
                            (Logo)</label>

                        @if(setting('site_logo'))
                            <div
                                class="mb-4 bg-gray-50 border border-gray-200 rounded p-4 flex items-center justify-center max-w-xs">
                                <img src="{{ Storage::url(setting('site_logo')) }}" alt="شعار الموقع الحالي"
                                    class="max-h-20 object-contain">
                            </div>
                        @endif

                        <input type="file" name="site_logo" id="site_logo" accept="image/*"
                            class="w-full text-sm border border-gray-300 rounded-md file:mr-0 file:ml-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-brand-primary hover:file:bg-gray-100 @error('site_logo') border-red-500 @enderror">

                        <p class="mt-2 text-xs text-gray-500 leading-tight">سيظهر هذا الشعار بدلا من العنوان النصي في
                            القائمة العلوية.<br>يفضل صورة شفافة بخلفية شفافة بصيغة PNG مقاس 250×50 بيكسل.</p>
                        @error('site_logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="flex">
                    <button type="submit"
                        class="px-8 py-3 bg-brand-primary text-white font-bold rounded shadow-sm hover:bg-[#0d9488] transition-colors duration-200">
                        حفظ الإعدادات
                    </button>
                </div>

            </div>
        </div>
    </form>
@endsection