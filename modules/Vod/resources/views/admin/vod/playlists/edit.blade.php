@extends('theme::layouts.admin')

@section('title', 'تعديل القائمة: ' . $playlist->title)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">تعديل القائمة</h1>
    <a href="{{ route('admin.vod.playlists.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
        العودة للقائمة
    </a>
</div>

<form action="{{ route('admin.vod.playlists.update', $playlist) }}" method="POST" id="playlist-form">
    @csrf
    @method('PUT')
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Column -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Title & Slug -->
            <div class="bg-white p-6 rounded shadow">
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">اسم القائمة</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $playlist->title) }}" 
                           class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror"
                           placeholder="أدخل اسم القائمة" required>
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">الرابط الدائم (Slug)</label>
                    <div class="flex items-center">
                        <span class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm" dir="ltr">{{ config('app.url') }}/playlists/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $playlist->slug) }}" 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                               dir="ltr">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white p-6 rounded shadow">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                <div class="@error('description') border border-red-500 rounded @enderror">
                    @php $value = old('description', $playlist->description); @endphp
                    @ckeditor('description')
                </div>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <!-- Videos Selector -->
            <div class="bg-white p-6 rounded shadow">
                <label class="block text-sm font-medium text-gray-700 mb-4">إضافة فيديوهات للقائمة</label>
                
                @if($contents->count() > 0)
                    <div class="border border-gray-200 rounded-md max-h-96 overflow-y-auto p-4 space-y-2 bg-gray-50">
                        @foreach($contents as $content)
                        <label class="flex items-start space-x-3 space-x-reverse cursor-pointer p-2 hover:bg-white rounded border border-transparent hover:border-gray-200 transition-colors">
                            <input type="checkbox" name="contents[]" value="{{ $content->id }}" 
                                   class="mt-1 h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-accent"
                                   {{ in_array($content->id, old('contents', $playlist->items->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <div class="flex-1">
                                <span class="text-gray-900 font-medium block">{{ $content->title }}</span>
                                <span class="text-xs text-gray-500 block">
                                    {{ $content->type === 'video' ? 'فيديو' : 'صوت' }} | 
                                    {{ $content->published_at ? $content->published_at->format('Y-m-d') : '' }}
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-gray-500">اختر الفيديوهات التي تريد إضافتها لهذه القائمة.</p>
                @else
                    <div class="text-center py-6 text-gray-500 bg-gray-50 rounded border border-dashed border-gray-300">
                        لا توجد فيديوهات منشورة حالياً لإضافتها.
                    </div>
                @endif
                @error('contents') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

        </div>

        <!-- Sidebar Column -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Actions Box -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">نشر</h3>
                <div class="pt-2">
                    <button type="submit" class="w-full px-6 py-2 bg-brand-primary text-white rounded hover:bg-[#0d9488] transition-colors duration-200 shadow-sm font-medium">
                        تحديث القائمة
                    </button>
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
            titleInput.addEventListener('blur', function() {
                if (slugInput.value.trim() === '') {
                    generateSlug();
                }
            });
            
            if (form) {
                form.addEventListener('submit', function(e) {
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

