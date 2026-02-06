@extends('theme::layouts.admin')

@section('title', 'طھط­ط±ظٹط± ظ‚ط³ظ…')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">طھط­ط±ظٹط± ظ‚ط³ظ…: {{ $category->name }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        ط§ظ„ط¹ظˆط¯ط© ظ„ظ„ظ‚ط§ط¦ظ…ط©
    </a>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" id="category-form">
    @csrf
    @method('PUT')
    
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="w-full lg:w-2/3 space-y-6">
            
            <div class="bg-white p-6 rounded shadow">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ط§ط³ظ… ط§ظ„ظ‚ط³ظ…</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                           class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('name') border-red-500 @enderror"
                           placeholder="ط£ط¯ط®ظ„ ط§ط³ظ… ط§ظ„ظ‚ط³ظ… ظ‡ظ†ط§">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط±ط§ط¨ط· ط§ظ„ط¯ط§ط¦ظ… (Slug)</label>
                    <div class="flex items-center">
                        <span class="text-gray-500 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm" dir="ltr">{{ config('app.url') }}/category/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('slug') border-red-500 @enderror text-sm"
                               dir="ltr">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">ط³ظٹطھظ… طھظˆظ„ظٹط¯ظ‡ طھظ„ظ‚ط§ط¦ظٹظ‹ط§ ظ…ظ† ط§ظ„ط§ط³ظ… ط¥ط°ط§ طھط±ظƒ ظپط§ط±ط؛ظ‹ط§.</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ظˆطµظپ</label>
                <textarea 
                    class="ckeditor" 
                    name="description" 
                    id="description"
                    data-placeholder="ط£ط¯ط®ظ„ ظˆطµظپ ط§ظ„ظ‚ط³ظ… ظ‡ظ†ط§..."
                    data-min-height="350px"
                >{{ old('description', $category->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
        </form>

        <div class="w-full lg:w-1/3 space-y-6">
            
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">ط§ظ„ط¥ط¹ط¯ط§ط¯ط§طھ</h3>
                
                <div class="mb-4">
                    <label for="order_column" class="block text-sm font-medium text-gray-700 mb-2">طھط±طھظٹط¨ ط§ظ„ط¹ط±ط¶</label>
                    <input type="number" name="order_column" id="order_column" 
                           value="{{ old('order_column', $category->order_column) }}" 
                           form="category-form"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent text-sm"
                           min="0">
                    <p class="mt-1 text-xs text-gray-500">ط§ظ„ط£ظ‚ط³ط§ظ… ط°ط§طھ ط§ظ„ط±ظ‚ظ… ط§ظ„ط£طµط؛ط± طھط¸ظ‡ط± ط£ظˆظ„ط§ظ‹</p>
                </div>

                <div class="flex items-center justify-between pt-4 border-t mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                        ط¥ظ„ط؛ط§ط،
                    </a>
                    <button type="submit" form="category-form" class="px-6 py-2 bg-brand-primary text-white rounded hover:bg-opacity-90 transition-colors text-sm font-medium shadow-sm">
                        طھط­ط¯ظٹط« ط§ظ„ظ‚ط³ظ…
                    </button>
                </div>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-3 text-sm">ط¥ط­طµط§ط¦ظٹط§طھ</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs text-gray-600">ط¹ط¯ط¯ ط§ظ„ظ…ظ‚ط§ظ„ط§طھ</span>
                        <span class="text-sm font-bold text-brand-accent">{{ $category->posts_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-xs text-gray-600">طھط§ط±ظٹط® ط§ظ„ط¥ظ†ط´ط§ط،</span>
                        <span class="text-xs text-gray-500">{{ $category->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 p-4 rounded shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 ml-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">ظ…ط§ظ‡ظٹ ط§ظ„ط£ظ‚ط³ط§ظ…طں</p>
                        <p class="text-xs">ط§ظ„ط£ظ‚ط³ط§ظ… طھط³ط§ط¹ط¯ ظپظٹ طھظ†ط¸ظٹظ… ط§ظ„ظ…ظ‚ط§ظ„ط§طھ ط­ط³ط¨ ط§ظ„ظ…ظˆط§ط¶ظٹط¹ ط§ظ„ط±ط¦ظٹط³ظٹط©. ظٹظ…ظƒظ† ظ„ظƒظ„ ظ…ظ‚ط§ظ„ ط£ظ† ظٹظ†طھظ…ظٹ ظ„ط¹ط¯ط© ط£ظ‚ط³ط§ظ….</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 p-4 rounded shadow-sm">
                <h3 class="font-bold text-red-800 mb-2 text-sm">ظ…ظ†ط·ظ‚ط© ط§ظ„ط®ط·ط±</h3>
                <p class="text-xs text-red-700 mb-3">ط­ط°ظپ ظ‡ط°ط§ ط§ظ„ظ‚ط³ظ… ط³ظٹط¤ط«ط± ط¹ظ„ظ‰ ط¬ظ…ظٹط¹ ط§ظ„ظ…ظ‚ط§ظ„ط§طھ ط§ظ„ظ…ط±طھط¨ط·ط© ط¨ظ‡.</p>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="js-confirm w-full px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-xs font-medium"
                            data-confirm-message="ظ‡ظ„ ط£ظ†طھ ظ…طھط£ظƒط¯ ظ…ظ† ط­ط°ظپ ظ‡ط°ط§ ط§ظ„ظ‚ط³ظ…طں ط³ظٹطھظ… ط¥ط²ط§ظ„ط© ط§ظ„ظ‚ط³ظ… ظ…ظ† ط¬ظ…ظٹط¹ ط§ظ„ظ…ظ‚ط§ظ„ط§طھ ط§ظ„ظ…ط±طھط¨ط·ط© ط¨ظ‡.">
                        ط­ط°ظپ ط§ظ„ظ‚ط³ظ…
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
@ckeditorScripts

<script>
    // Auto-Slug Generator (Arabic Friendly)
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('blur', function() {
                // Only generate if slug is empty
                if (slugInput.value.trim() === '') {
                    const name = this.value;
                    const slug = name.trim()
                        .replace(/\s+/g, '-')           // Replace spaces with -
                        .replace(/[^\w\u0600-\u06FF\-]+/g, '') // Remove non-word chars (preserving Arabic & -)
                        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                        .replace(/^-+/, '')             // Trim - from start
                        .replace(/-+$/, '');            // Trim - from end
                    
                    slugInput.value = slug;
                }
            });
            
            // Force generation on form submit (before sending to server)
            const form = document.getElementById('category-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!slugInput.value.trim() && nameInput.value.trim()) {
                        // Generate slug instantly before sending
                        let slug = nameInput.value.trim()
                            .replace(/\s+/g, '-')           // Replace spaces with -
                            .replace(/[^\w\u0600-\u06FF\-]+/g, '') // Keep Arabic & English chars & numbers
                            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                            .replace(/^-+/, '')             // Trim - from start
                            .replace(/-+$/, '');            // Trim - from end
                        
                        slugInput.value = slug;
                    }
                });
            }
        }
    });
</script>
@endpush
