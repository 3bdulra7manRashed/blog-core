@extends('theme::layouts.admin')

@section('title', 'إنشاء خاطرة جديدة')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">إنشاء خاطرة جديدة</h1>
    <a href="{{ route('admin.thoughts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        العودة للقائمة
    </a>
</div>

<form action="{{ route('admin.thoughts.store') }}" method="POST" enctype="multipart/form-data" id="thought-form">
    @csrf
    @include('thoughts::admin.thoughts._form', ['thought' => null])
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image Preview Script
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
    });
</script>
@endpush
