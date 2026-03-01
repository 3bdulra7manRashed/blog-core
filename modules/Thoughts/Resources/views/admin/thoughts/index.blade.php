@extends('theme::layouts.admin')

@section('title', 'الخواطر')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">الخواطر</h1>
        <a href="{{ route('admin.thoughts.create') }}"
            class="flex items-center px-4 py-2 bg-brand-accent text-white hover:bg-[#2f5c8f] hover:text-white rounded-md hover:bg-opacity-90 transition-colors shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            خاطرة جديدة
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('admin.thoughts.index') }}"
            class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث في الخواطر..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent">
            <select name="status"
                class="py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent">
                <option value="">جميع الحالات</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-[#0d9488] transition-colors duration-200 whitespace-nowrap">تصفية</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.thoughts.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-center whitespace-nowrap">مسح</a>
            @endif
        </form>
    </div>

    <!-- Thoughts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                            العنوان</th>
                        <th
                            class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            الترتيب</th>
                        <th
                            class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            الحالة</th>
                        <th
                            class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            مميزة</th>
                        <th
                            class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            تاريخ النشر</th>
                        <th
                            class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($thoughts as $thought)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 md:px-6 py-4 min-w-[200px]">
                                <div class="flex flex-col items-center text-center gap-y-2 md:block md:text-center">
                                    <span class="text-sm font-medium text-brand-primary line-clamp-2">
                                        {{ $thought->title }}
                                    </span>
                                    <!-- Mobile: Status Badge -->
                                    <div class="md:hidden">
                                        @if(!$thought->is_published)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                مسودة
                                            </span>
                                        @elseif($thought->isPublished())
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                منشور
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                مجدول
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td
                                class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center hidden md:table-cell">
                                {{ $thought->sort_order }}
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                                @if(!$thought->is_published)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        مسودة
                                    </span>
                                @elseif($thought->isPublished())
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        منشور
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        مجدول
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                                @if($thought->is_featured)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        ⭐ مميزة
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td
                                class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center hidden md:table-cell">
                                {{ $thought->published_at ? $thought->published_at->format('Y/m/d') : '-' }}
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.thoughts.edit', $thought) }}"
                                        class="text-brand-accent hover:text-amber-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.thoughts.destroy', $thought) }}" method="POST"
                                        class="inline-flex items-center m-0 p-0"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذه الخاطرة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">لا توجد خواطر</p>
                                <p class="text-sm mt-1">ابدأ بإنشاء خاطرة جديدة</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($thoughts->hasPages())
        <div class="mt-6">
            {{ $thoughts->links() }}
        </div>
    @endif
@endsection