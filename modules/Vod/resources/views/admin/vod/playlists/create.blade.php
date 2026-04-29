@extends('theme::layouts.admin')

@section('title', 'إضافة قائمة تشغيل جديدة')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">إضافة قائمة تشغيل</h1>
        <a href="{{ route('admin.vod.playlists.index') }}"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            العودة للقائمة
        </a>
    </div>

    <form action="{{ route('admin.vod.playlists.store') }}" method="POST" id="playlist-form"
        x-data="{ type: '{{ old('type', 'video') }}' }">
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

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Column (Content) -->
            <div class="w-full lg:w-2/3 space-y-4">

                <!-- Title & Slug -->
                <div class="bg-white p-6 rounded shadow">
                    <!-- Title -->
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">اسم القائمة <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                            placeholder="أدخل اسم القائمة" required>
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug (Standard Size to Match Posts) -->
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">الرابط الدائم (Slug)</label>
                        <div class="flex items-center">
                            <span
                                class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm"
                                dir="ltr">{{ config('app.url') }}/playlists/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                                dir="ltr">
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100 my-5"></div>

                    <!-- Description (Using Compact Profile) -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-600 mb-2">وصف مختصر للقائمة
                            <span class="text-gray-400 text-xs font-normal">(اختياري)</span></label>
                        <div class="@error('description') border border-red-500 rounded @enderror">
                            @ckeditor('description', 'compact')
                        </div>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <!-- Sidebar Column (Meta & Actions) -->
            <div class="w-full lg:w-1/3 space-y-6">
                <!-- Type Selector (New Layout) -->
                <div class="bg-white rounded shadow p-4">
                    <h3 class="font-bold text-[#1F3A6E] mb-3 border-b pb-2">نوع القائمة</h3>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 space-x-reverse cursor-pointer">
                            <input type="radio" name="type" value="video" x-model="type"
                                class="form-radio h-5 w-5 text-brand-primary focus:ring-brand-accent">
                            <span class="text-gray-900 font-medium">فيديو</span>
                        </label>
                        @if(feature('vod.audio'))
                        <label class="flex items-center space-x-3 space-x-reverse cursor-pointer">
                            <input type="radio" name="type" value="audio" x-model="type"
                                class="form-radio h-5 w-5 text-brand-primary focus:ring-brand-accent">
                            <span class="text-gray-900 font-medium">صوت</span>
                        </label>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-2">تغيير النوع سيقوم بتصفية المحتوى المتاح في الأسفل.</p>
                </div>

                <!-- Media Selection Card (Priority 1) -->
                <div class="bg-white rounded shadow border-t-4 border-brand-accent overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="font-bold text-[#1F3A6E]">محتوى القائمة</h3>
                        <span class="text-xs bg-gray-200 px-2 py-0.5 rounded text-gray-600">{{ $contents->count() }}
                            متاح</span>
                    </div>

                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-3">اختر الفيديوهات والصوتيات لتضمينها في هذه القائمة.</p>

                        @if($contents->count() > 0)
                            <div
                                class="border border-gray-200 rounded-md max-h-80 overflow-y-auto bg-gray-50 divide-y divide-gray-100">
                                @foreach($contents as $content)
                                    <label
                                        class="flex items-start space-x-2 space-x-reverse cursor-pointer p-2 hover:bg-white transition-colors group"
                                        x-show="type == '{{ $content->type }}'">
                                        <input type="checkbox" name="contents[]" value="{{ $content->id }}"
                                            class="mt-1 h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-accent flex-shrink-0"
                                            {{ in_array($content->id, old('contents', [])) ? 'checked' : '' }}>
                                        <div class="flex-1 min-w-0">
                                            <span
                                                class="text-gray-800 font-medium text-sm block truncate group-hover:text-brand-primary transition-colors">{{ $content->title }}</span>
                                            <div class="flex items-center gap-1 text-[10px] text-gray-400 mt-0.5">
                                                <span>{{ $content->type === 'video' ? 'فيديو' : 'صوت' }}</span>
                                                <span>•</span>
                                                <span>{{ $content->published_at ? $content->published_at->format('Y-m-d') : '-' }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-500 border border-dashed border-gray-200 rounded">
                                <p class="text-sm">لا يوجد محتوى متاح.</p>
                                <a href="{{ route('admin.vod.contents.create') }}"
                                    class="text-brand-primary text-xs hover:underline mt-1 block">إضافة جديد</a>
                            </div>
                        @endif
                        @error('contents') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Publish Box (Priority 2) -->
                <div class="bg-white p-4 rounded shadow sticky top-6">
                    <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">نشر</h3>
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full px-6 py-2.5 bg-brand-primary text-white rounded hover:bg-[#0d9488] transition-colors duration-200 shadow-sm font-bold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            حفظ القائمة
                        </button>
                        <p class="text-xs text-gray-400 mt-3 text-center">تأكد من اختيار المحتوى قبل الحفظ.</p>
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
            // Auto-Slug Generator
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            const form = document.getElementById('playlist-form');

            if (titleInput && slugInput) {
                titleInput.addEventListener('blur', function () {
                    if (slugInput.value.trim() === '') {
                        generateSlug();
                    }
                });

                if (form) {
                    form.addEventListener('submit', function (e) {
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