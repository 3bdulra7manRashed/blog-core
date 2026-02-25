@php
    /**
     * Reusable Admin Thumbnail Field Partial
     *
     * @param ?Model  $model           The model instance (null on create)
     * @param string  $uploadField     File input name (e.g. 'featured_image', 'thumbnail', 'image', 'cover_file')
     * @param string  $urlField        External URL input name (e.g. 'thumbnail_url', 'cover_url')
     * @param string  $label           Section heading label
     * @param ?string $altField        Alt text input name (null to hide)
     * @param ?string $altHelpText     Help text below the alt input
     * @param ?string $currentImageUrl Full URL of the current image (for edit mode preview)
     * @param ?string $currentImageRaw Raw DB value to determine if upload-placeholder should be hidden
     */

    $model = $model ?? null;
    $uploadField = $uploadField ?? 'thumbnail';
    $urlField = $urlField ?? 'thumbnail_url';
    $label = $label ?? 'الصورة المصغرة';
    $altField = $altField ?? null;
    $altHelpText = $altHelpText ?? null;
    $currentImageUrl = $currentImageUrl ?? null;
    $currentImageRaw = $currentImageRaw ?? null;

    // Generate unique IDs to avoid conflicts if partial is used multiple times
    $inputId = $uploadField;
    $previewId = 'image-preview';
    $placeholderId = 'upload-placeholder';
@endphp

<div class="bg-white p-4 rounded shadow">
    <h3 class="font-bold text-[#1F3A6E] mb-4 border-b pb-2">{{ $label }}</h3>
    <div class="space-y-3">

        {{-- Current Image Preview (edit mode) --}}
        @if($currentImageUrl)
            <div class="mb-2">
                <img src="{{ $currentImageUrl }}" alt="{{ $model?->title ?? 'Preview' }}"
                    class="w-full h-48 object-cover rounded" id="current-image-preview">
            </div>
        @endif

        {{-- File Upload Drop Zone --}}
        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand-accent transition-colors cursor-pointer"
            onclick="document.getElementById('{{ $inputId }}').click()">
            <div id="{{ $previewId }}" class="hidden mb-2">
                <img src="" alt="Preview" class="max-h-48 mx-auto rounded">
            </div>
            <div id="{{ $placeholderId }}" class="{{ $currentImageRaw ? 'hidden' : '' }}">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="mt-1 text-sm text-gray-600">انقر لرفع صورة</p>
                <p class="text-xs text-gray-500">PNG, JPG, GIF (Max 3MB)</p>
            </div>
        </div>
        <input type="file" name="{{ $uploadField }}" id="{{ $inputId }}" accept="image/*" class="hidden"
            onchange="previewImage(this)">

        {{-- Alt Text Field (optional — only Posts/Khutab use this) --}}
        @if($altField)
            <div>
                <label for="{{ $altField }}" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-accent flex-shrink-0"></span>
                    النص البديل
                </label>
                <input type="text" name="{{ $altField }}" id="{{ $altField }}"
                    value="{{ old($altField, $model?->$altField ?? '') }}"
                    class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-brand-accent"
                    placeholder="وصف للصورة">
                @if($altHelpText)
                    <p class="mt-1 text-xs text-gray-500">{{ $altHelpText }}</p>
                @endif
            </div>
        @endif

        {{-- External URL Input --}}
        <div>
            <label for="{{ $urlField }}" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-accent flex-shrink-0"></span>
                أو رابط صورة خارجية
            </label>
            <input type="url" name="{{ $urlField }}" id="{{ $urlField }}"
                value="{{ old($urlField, $model?->$urlField ?? '') }}"
                class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-brand-accent"
                placeholder="https://example.com/image.jpg" dir="ltr">
            <p class="mt-1 text-xs text-gray-500">
                رابط صورة خارجية (يُستخدم إذا لم ترفع ملفًا)
            </p>
        </div>

        {{-- Validation Errors --}}
        @error($uploadField)
            <p class="text-xs text-red-600">{{ $message }}</p>
        @enderror
        @error($urlField)
            <p class="text-xs text-red-600">{{ $message }}</p>
        @enderror

    </div>
</div>