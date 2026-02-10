<?php

namespace Modules\Thoughts\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThoughtRequest extends FormRequest
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
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الخاطرة مطلوب',
            'title.string' => 'عنوان الخاطرة يجب أن يكون نصاً',
            'title.max' => 'عنوان الخاطرة يجب ألا يتجاوز 255 حرفاً',
            'content.required' => 'محتوى الخاطرة مطلوب',
            'content.string' => 'محتوى الخاطرة يجب أن يكون نصاً',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
            'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً',
        ];
    }
}
