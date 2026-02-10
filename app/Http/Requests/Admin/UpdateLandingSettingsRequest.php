<?php

namespace App\Http\Requests\Admin;

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
     */
    public function rules(): array
    {
        return [
            'hero_title'        => ['nullable', 'string', 'max:255'],
            'hero_subtitle'     => ['nullable', 'string'],
            'hero_image'        => ['nullable', 'string', 'max:255'],
            'cta_text'          => ['nullable', 'string', 'max:255'],
            'cta_link'          => ['nullable', 'url', 'max:255'],
            'show_quotes_section' => ['nullable', 'boolean'],
            'show_thoughts'     => ['nullable', 'boolean'],
            'show_category_one' => ['nullable', 'boolean'],
            'category_one_id'   => ['nullable', 'integer'],
            'show_category_two' => ['nullable', 'boolean'],
            'category_two_id'   => ['nullable', 'integer'],
            'show_khutab'       => ['nullable', 'boolean'],
            'khutab_category_id' => ['nullable', 'integer'],
            'show_releases'     => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'hero_title.max'    => 'عنوان الصفحة الرئيسية يجب ألا يتجاوز 255 حرفاً',
            'hero_image.max'    => 'مسار الصورة يجب ألا يتجاوز 255 حرفاً',
            'cta_text.max'      => 'نص الزر يجب ألا يتجاوز 255 حرفاً',
            'cta_link.url'      => 'رابط الزر يجب أن يكون رابطاً صالحاً',
            'cta_link.max'      => 'رابط الزر يجب ألا يتجاوز 255 حرفاً',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'hero_title'        => 'عنوان الصفحة',
            'hero_subtitle'     => 'العنوان الفرعي',
            'hero_image'        => 'صورة الخلفية',
            'cta_text'          => 'نص الزر',
            'cta_link'          => 'رابط الزر',
            'show_quotes_section' => 'قسم أحدث المقالات',
            'show_thoughts'     => 'قسم الخواطر',
            'show_category_one' => 'القسم الأول',
            'category_one_id'   => 'تصنيف القسم الأول',
            'show_category_two' => 'القسم الثاني',
            'category_two_id'   => 'تصنيف القسم الثاني',
            'show_khutab'       => 'قسم الخطب',
            'khutab_category_id' => 'تصنيف الخطب',
            'show_releases'     => 'قسم الإصدارات',
        ];
    }
}
