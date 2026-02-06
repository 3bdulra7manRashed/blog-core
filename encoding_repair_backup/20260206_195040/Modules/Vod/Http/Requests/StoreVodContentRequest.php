<?php

namespace Modules\Vod\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVodContentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Admin/Moderator checked by middleware
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:video,audio'],
            'embed_code' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'], // 2MB max
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'يرجى إدخال العنوان',
            'type.required' => 'يرجى اختيار النوع',
            'embed_code.required' => 'يرجى إدخال كود التضمين أو الرابط',
            'status.required' => 'يرجى تحديد الحالة',
        ];
    }
}
