@extends('theme::layouts.admin')

@section('title', 'مكتبة الوسائط')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-serif font-bold text-brand-primary mb-4">مكتبة الوسائط</h1>
    
    <form action="{{ route('admin.media.library.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        @csrf
        <div class="flex-1">
            <input type="file" name="file" accept=".jpeg,.jpg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" required 
                   class="block w-full text-sm text-gray-900 border rounded-lg cursor-pointer bg-gray-50 focus:outline-none 
                          border-gray-300
                          file:mr-0 file:py-2 file:px-4 
                          file:rounded-lg file:border-0 
                          file:text-sm file:font-semibold 
                          file:bg-brand-accent file:text-white 
                          hover:file:bg-amber-700 file:cursor-pointer">
        </div>
        <button type="submit" class="flex items-center justify-center px-4 py-2 bg-brand-accent text-white rounded-lg hover:bg-amber-700 transition-colors shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            رفع ملف
        </button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    @forelse($media as $item)
        @php
            $cleanPath = str_replace('public/', '', $item->path);
            $imageUrl = asset('storage/' . $cleanPath);
        @endphp
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col">
            <div class="aspect-square bg-gray-50 relative overflow-hidden border-b border-gray-100 group-hover:border-gray-200">
                <img src="{{ $imageUrl }}" 
                     alt="{{ $item->filename }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                     onclick="window.open('{{ $imageUrl }}', '_blank')">
            </div>

            <div class="p-3 flex flex-col gap-3 flex-1">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-medium text-gray-700 truncate flex-1" title="{{ $item->filename }}">
                        {{ $item->filename }}
                    </p>
                    <span class="shrink-0 text-[10px] font-bold text-gray-500 bg-gray-100 border border-gray-200 px-1.5 py-0.5 rounded">
                        {{ number_format($item->size / 1024, 1) }} KB
                    </span>
                </div>

                <div class="flex items-center gap-2 mt-auto">
                    <button type="button" 
                            onclick="navigator.clipboard.writeText('{{ $imageUrl }}'); alert('تم النسخ');"
                            class="flex-1 flex items-center justify-center gap-1.5 bg-gray-50 hover:bg-brand-accent hover:text-white text-gray-600 border border-gray-200 py-1.5 px-2 rounded transition-colors duration-200 text-xs font-medium group/btn shadow-sm">
                        نسخ
                    </button>

                    <form action="{{ route('admin.media.library.destroy', $item->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="js-confirm p-1.5 text-red-600 hover:text-white hover:bg-red-500 rounded border border-red-100 hover:border-red-500 transition-colors shadow-sm" 
                                onclick="return confirm('هل أنت متأكد؟')"
                                title="حذف الملف">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-16 text-center text-gray-500">
            لا توجد ملفات وسائط حالياً
        </div>
    @endforelse
</div>

@if($media->hasPages())
    <div class="mt-8" dir="ltr">
        {{ $media->links() }}
    </div>
@endif
@endsection
