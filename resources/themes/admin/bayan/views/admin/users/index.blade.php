@extends('theme::layouts.admin')

@section('title', 'المديرين')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">المديرين</h1>
    
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 bg-white rounded-md shadow-sm p-1">
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}" 
               class="px-3 py-1 text-xs rounded {{ request('status') === 'active' ? 'bg-brand-accent text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                نشط
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'deleted']) }}" 
               class="px-3 py-1 text-xs rounded {{ request('status') === 'deleted' ? 'bg-brand-accent text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                محذوف
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="px-3 py-1 text-xs rounded {{ !request('status') ? 'bg-brand-accent text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                الكل
            </a>
        </div>

        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-accent text-white hover:bg-teal-700 hover:text-white rounded-md hover:bg-opacity-90 transition-colors shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>إضافة مدير جديد</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">البريد الإلكتروني</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">الأدوار</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">تاريخ التسجيل</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors {{ $user->trashed() ? 'opacity-60 bg-gray-50' : '' }}">
                        <td class="px-4 md:px-6 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <div class="flex-shrink-0 h-8 w-8 ml-3">
                                    <div class="h-8 w-8 rounded-full bg-brand-accent text-white flex items-center justify-center font-semibold text-xs border border-teal-200">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-mono hidden md:table-cell">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                            @if($user->is_super_admin)
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    مدير النظام
                                </span>
                            @elseif($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $role->name === 'admin' ? 'مدير النظام' : ($role->name === 'moderator' ? 'مشرف' : $role->name) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مستخدم</span>
                            @endif
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center hidden lg:table-cell">
                            {{ $user->created_at->format('Y/m/d') }}
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center">
                            @if($user->trashed())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    محذوف
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($user->trashed())
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                        @csrf
                                        <button type="submit" class="js-confirm inline-flex items-center px-2 py-1 bg-green-50 text-green-700 rounded hover:bg-green-100 hover:text-green-800 font-medium text-xs transition-colors duration-200"
                                                data-confirm-message="هل أنت متأكد من استعادة هذا المستخدم؟">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            استعادة
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.forceDelete', $user->id) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="js-confirm inline-flex items-center px-2 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100 hover:text-red-800 font-medium text-xs transition-colors duration-200"
                                                data-confirm-message="تحذير: هل أنت متأكد من الحذف النهائي؟">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            حذف نهائي
                                        </button>
                                    </form>
                                @else
                                    {{-- Self/Protected User Check (Optional, visual only as backend protects too) --}}
                                    @if(auth()->id() !== $user->id && $user->id !== 1)
                                        
                                        @if($user->hasRole('admin'))
                                            {{-- Demote --}}
                                            <form action="{{ route('admin.users.demote', $user) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                                @csrf
                                                <button type="submit" class="js-confirm inline-flex items-center px-2 py-1 bg-teal-50 text-teal-700 rounded hover:bg-teal-100 hover:text-teal-800 font-medium text-xs transition-colors duration-200"
                                                        data-confirm-message="هل أنت متأكد من إزالة صلاحيات المشرف؟">
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path>
                                                    </svg>
                                                    إزالة
                                                </button>
                                            </form>
                                        @else
                                            {{-- Promote --}}
                                            <form action="{{ route('admin.users.promote', $user) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                                @csrf
                                                <button type="submit" class="js-confirm inline-flex items-center px-2 py-1 bg-green-50 text-green-700 rounded hover:bg-green-100 hover:text-green-800 font-medium text-xs transition-colors duration-200"
                                                        data-confirm-message="هل أنت متأكد من ترقية هذا المستخدم إلى مشرف؟">
                                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                    ترقية
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="js-confirm inline-flex items-center px-2 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100 hover:text-red-800 font-medium text-xs transition-colors duration-200"
                                                    data-confirm-message="هل أنت متأكد من حذف هذا المستخدم؟">
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                حذف
                                            </button>
                                        </form>

                                    @else
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-400 rounded-md font-medium text-xs">
                                            محمي
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 md:px-6 py-8 text-center text-gray-500">
                            لا يوجد مديرين
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
    <div class="mt-6" dir="ltr">
        {{ $users->links() }}
    </div>
@endif
@endsection
