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

    <form action="{{ route('admin.settings.general.update') }}" method="POST">
        @csrf
        @method('PUT')
        {{-- Hidden input: holds Base64 cropped image data --}}
        <input type="hidden" name="site_logo_base64" id="site_logo_base64">

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
                        <label class="block text-sm font-medium text-gray-700 mb-3">شعار الموقع (Logo)</label>

                        {{-- Current Logo Preview --}}
                        @if(setting('site_logo'))
                            <div class="mb-4 flex items-center gap-4">
                                <div class="relative">
                                    <img src="{{ setting('site_logo') }}" alt="شعار الموقع الحالي"
                                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm bg-gray-50">
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">الشعار الحالي</p>
                                    <p class="text-xs text-gray-400 mt-1 max-w-xs truncate" dir="ltr">{{ setting('site_logo') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Dual Option Tabs --}}
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            {{-- Tab Headers --}}
                            <div class="flex border-b border-gray-200 bg-gray-50">
                                <button type="button" id="tab-upload"
                                    class="flex-1 px-4 py-3 text-sm font-medium text-center transition-colors border-b-2 border-brand-accent text-brand-accent bg-white"
                                    onclick="switchTab('upload')">
                                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    رفع صورة وقصها
                                </button>
                                <button type="button" id="tab-url"
                                    class="flex-1 px-4 py-3 text-sm font-medium text-center transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                                    onclick="switchTab('url')">
                                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                    رابط مباشر
                                </button>
                            </div>

                            {{-- Tab: Upload & Crop --}}
                            <div id="panel-upload" class="p-5">
                                <div class="space-y-4">
                                    {{-- File Input --}}
                                    <div>
                                        <label for="logo_file" class="block text-xs font-medium text-gray-600 mb-2">اختر
                                            صورة من جهازك</label>
                                        <input type="file" id="logo_file" accept="image/*"
                                            class="w-full text-sm border border-gray-300 rounded-md file:mr-0 file:ml-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-brand-primary hover:file:bg-gray-100 cursor-pointer">
                                        <p class="mt-1 text-xs text-gray-400">يفضل صورة مربعة بصيغة PNG بخلفية شفافة</p>
                                    </div>

                                    {{-- Cropper Container (hidden by default) --}}
                                    <div id="cropper-container" class="hidden">
                                        <div class="bg-gray-900 rounded-lg p-4 relative">
                                            <div class="max-h-80 overflow-hidden flex items-center justify-center">
                                                <img id="cropper-image" src="" alt="crop preview" class="max-w-full">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between mt-3">
                                            <p class="text-xs text-gray-500">حرّك وكبّر/صغّر لضبط القص الدائري</p>
                                            <div class="flex gap-2">
                                                <button type="button" id="btn-cancel-crop"
                                                    class="px-4 py-2 text-sm border border-gray-300 text-gray-600 rounded-md hover:bg-gray-50 transition-colors">
                                                    إلغاء
                                                </button>
                                                <button type="button" id="btn-confirm-crop"
                                                    class="px-4 py-2 text-sm bg-brand-accent text-white rounded-md hover:bg-teal-700 transition-colors font-medium">
                                                    تأكيد القص
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Cropped Preview (shown after confirmation) --}}
                                    <div id="cropped-preview" class="hidden">
                                        <div
                                            class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                            <img id="cropped-preview-img" src="" alt="معاينة الشعار المقصوص"
                                                class="w-20 h-20 rounded-full object-cover border-2 border-green-300 shadow-md">
                                            <div>
                                                <p class="text-sm font-medium text-green-700">تم قص الشعار بنجاح</p>
                                                <p class="text-xs text-green-600 mt-1">سيتم حفظ الشعار المقصوص عند الضغط على
                                                    "حفظ الإعدادات"</p>
                                                <button type="button" id="btn-recrop"
                                                    class="mt-2 text-xs text-green-700 underline hover:text-green-900">
                                                    إعادة القص
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab: URL --}}
                            <div id="panel-url" class="p-5 hidden">
                                <label for="site_logo_url" class="block text-xs font-medium text-gray-600 mb-2">رابط الشعار
                                    المباشر</label>
                                <input type="text" name="site_logo_url" id="site_logo_url"
                                    value="{{ old('site_logo_url') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent"
                                    placeholder="https://example.com/logo.png" dir="ltr">
                                <p class="mt-2 text-xs text-gray-500">أدخل مسار الشعار (مسار نسبي داخل storage أو رابط خارجي
                                    كامل)</p>
                            </div>
                        </div>

                        @error('site_logo_url')
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

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"
        integrity="sha512-UtLOu9C7QLCJ3fS0+JOayxiH1lLvJBk2xkA3gNE+JXSNr3GZEgFiKOMsCEmVNzXYMiAEPfAjGLSxbr1Iy0GRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Make the cropper view-box circular */
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }

        .cropper-view-box {
            outline: 0;
            box-shadow: 0 0 0 1px rgba(14, 165, 233, 0.75), 0 0 0 3000px rgba(0, 0, 0, 0.55);
        }

        .cropper-face {
            background-color: transparent !important;
        }

        .cropper-dashed,
        .cropper-line {
            display: none !important;
        }

        .cropper-point {
            background-color: #0ea5e9 !important;
            width: 8px !important;
            height: 8px !important;
            opacity: 0.9 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"
        integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1b6fNUTpDDogUJrq2Xib22Kx7Ch8CGID28V/EFmT+KOEO/MHfbkVEIaA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var cropper = null;
            var fileInput = document.getElementById('logo_file');
            var cropperContainer = document.getElementById('cropper-container');
            var cropperImage = document.getElementById('cropper-image');
            var confirmBtn = document.getElementById('btn-confirm-crop');
            var cancelBtn = document.getElementById('btn-cancel-crop');
            var recropBtn = document.getElementById('btn-recrop');
            var croppedPreview = document.getElementById('cropped-preview');
            var croppedPreviewImg = document.getElementById('cropped-preview-img');
            var base64Input = document.getElementById('site_logo_base64');

            // Tab switching
            window.switchTab = function (tab) {
                var tabUpload = document.getElementById('tab-upload');
                var tabUrl = document.getElementById('tab-url');
                var panelUpload = document.getElementById('panel-upload');
                var panelUrl = document.getElementById('panel-url');

                if (tab === 'upload') {
                    tabUpload.classList.add('border-brand-accent', 'text-brand-accent', 'bg-white');
                    tabUpload.classList.remove('border-transparent', 'text-gray-500');
                    tabUrl.classList.remove('border-brand-accent', 'text-brand-accent', 'bg-white');
                    tabUrl.classList.add('border-transparent', 'text-gray-500');
                    panelUpload.classList.remove('hidden');
                    panelUrl.classList.add('hidden');
                } else {
                    tabUrl.classList.add('border-brand-accent', 'text-brand-accent', 'bg-white');
                    tabUrl.classList.remove('border-transparent', 'text-gray-500');
                    tabUpload.classList.remove('border-brand-accent', 'text-brand-accent', 'bg-white');
                    tabUpload.classList.add('border-transparent', 'text-gray-500');
                    panelUrl.classList.remove('hidden');
                    panelUpload.classList.add('hidden');
                }
            };

            // File input change: load image into cropper
            fileInput.addEventListener('change', function (e) {
                var file = e.target.files[0];
                if (!file) return;

                var reader = new FileReader();
                reader.onload = function (ev) {
                    // Destroy any old cropper
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }

                    // Show container, hide preview
                    cropperContainer.classList.remove('hidden');
                    croppedPreview.classList.add('hidden');
                    base64Input.value = '';

                    // Set source and initialize cropper
                    cropperImage.src = ev.target.result;

                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.85,
                        restore: false,
                        guides: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        responsive: true,
                        minCropBoxWidth: 64,
                        minCropBoxHeight: 64,
                    });
                };
                reader.readAsDataURL(file);
            });

            // Confirm crop
            confirmBtn.addEventListener('click', function () {
                if (!cropper) return;

                var canvas = cropper.getCroppedCanvas({
                    width: 512,
                    height: 512,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                var dataURL = canvas.toDataURL('image/png');

                // Store in hidden input
                base64Input.value = dataURL;

                // Show preview
                croppedPreviewImg.src = dataURL;
                croppedPreview.classList.remove('hidden');

                // Hide cropper
                cropperContainer.classList.add('hidden');
                cropper.destroy();
                cropper = null;
            });

            // Cancel crop
            cancelBtn.addEventListener('click', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                cropperContainer.classList.add('hidden');
                fileInput.value = '';
            });

            // Re-crop
            recropBtn.addEventListener('click', function () {
                croppedPreview.classList.add('hidden');
                base64Input.value = '';
                fileInput.value = '';
                fileInput.click();
            });
        });
    </script>
@endpush