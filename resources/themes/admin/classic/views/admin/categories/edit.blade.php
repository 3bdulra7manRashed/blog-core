@extends('theme::layouts.admin')

@section('title', 'تعديل القسم')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">تعديل القسم</h1>
    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        إلغاء
    </a>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="max-w-4xl">
    @csrf
    @method('PUT')
    
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent -->
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">القسم الأب</label>
                <select name="parent_id" id="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent">
                    <option value="">بدون (قسم رئيسي)</option>
                    @foreach(\Modules\Blog\Entities\Category::where('id', '!=', $category->id)->pluck('name', 'id') as $id => $name)
                        <option value="{{ $id }}" {{ old('parent_id', $category->parent_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">الرابط</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent @error('slug') border-red-500 @enderror"
                       dir="ltr">
                <p class="mt-1 text-xs text-gray-500">سيتم توليده تلقائياً في حال تركه فارغاً.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

             <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                <textarea name="description" id="description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand-accent focus:border-brand-accent">{{ old('description', $category->description) }}</textarea>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">إلغاء</a>
            <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded-md hover:bg-opacity-90 transition-colors font-medium">
                حفظ
            </button>
        </div>
    </div>
</form>
@endsection
