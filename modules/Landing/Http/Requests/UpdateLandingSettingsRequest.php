<?php

namespace Modules\Landing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Auth handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'hero_image_url' => ['nullable', 'string', 'max:500'],
            'hero_mobile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'hero_mobile_image_url' => ['nullable', 'string', 'max:500'],
            'show_quotes_section' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hero_title.max' => 'عنوان الصفحة الرئيسية يجب ألا يتجاوز 255 حرفاً',
            'hero_subtitle.max' => 'العنوان الفرعي يجب ألا يتجاوز 1000 حرف',
            'hero_image.image' => 'الملف يجب أن يكون صورة',
            'hero_image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
            'hero_mobile_image.image' => 'الملف يجب أن يكون صورة',
            'hero_mobile_image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'hero_title' => 'عنوان الصفحة',
            'hero_subtitle' => 'العنوان الفرعي',
            'hero_image' => 'صورة الخلفية',
            'hero_mobile_image' => 'صورة الموبايل (Hero Mobile)',
            'show_quotes_section' => 'قسم الاقتباسات',
        ];
    }
}
