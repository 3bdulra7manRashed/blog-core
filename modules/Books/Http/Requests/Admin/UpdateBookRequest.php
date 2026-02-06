<?php

namespace Modules\Books\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('books', 'slug')->ignore($this->route('book')),
            ],
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'external_url' => 'required|url',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'عنوان الكتاب',
            'slug' => 'الرابط الدائم',
            'excerpt' => 'المقتطف',
            'description' => 'الوصف',
            'cover_image' => 'صورة الغلاف',
            'external_url' => 'رابط الشراء',
            'status' => 'الحالة',
            'published_at' => 'تاريخ النشر',
        ];
    }
}
