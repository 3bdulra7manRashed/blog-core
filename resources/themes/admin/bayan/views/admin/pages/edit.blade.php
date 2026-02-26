@extends('theme::layouts.admin')

@section('title', 'تعديل صفحة عن المدونة')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">تعديل صفحة "عن المدونة"</h1>
</div>

<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Column -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Title -->
            <div class="bg-white p-6 rounded shadow">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الصفحة</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" 
                           class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white p-6 rounded shadow">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">المحتوى</label>
                <div class="@error('content') border border-red-500 rounded @enderror">
                    @ckeditor('content', $page->content)
                </div>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Actions -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">نشر التغييرات</h3>
                <button type="submit" class="w-full px-6 py-2 bg-brand-primary text-white rounded hover:bg-opacity-90 transition-colors text-sm font-medium shadow-sm">
                    حفظ التغييرات
                </button>
            </div>

            <!-- Featured Image -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">الصورة البارزة</h3>
                <div class="space-y-3">
                    @if($page->featured_image)
                        <div class="mb-2">
                            <p class="text-xs text-gray-500 mb-1">الصورة الحالية:</p>
                            <img src="{{ asset('storage/' . $page->featured_image) }}" alt="Current Image" class="w-full rounded border">
                        </div>
                    @endif

                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand-accent transition-colors cursor-pointer" onclick="document.getElementById('featured_image').click()">
                        <div id="image-preview" class="hidden mb-2">
                            <img src="" alt="Preview" class="max-h-48 mx-auto rounded">
                        </div>
                        <div id="upload-placeholder">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-600">انقر لتغيير الصورة</p>
                        </div>
                    </div>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    
                    @error('featured_image')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
@ckeditorScripts
<script>
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
</script>
@endpush

