<textarea 
    class="ckeditor" 
    name="{{ $fieldName }}" 
    id="{{ $id ?? $fieldName }}"
    data-placeholder="{{ $placeholder ?? 'ابدأ الكتابة هنا...' }}"
    data-profile="{{ $profile ?? 'default' }}"
    {{ $attributes ?? '' }}
>{{ $value ?? '' }}</textarea>
