<?php

namespace Modules\Vod\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreVodContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only Admin or Moderator
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isModerator());
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // 1. Handle Action -> Status Mapping
        if ($this->has('action')) {
            $this->merge([
                'status' => $this->action === 'publish' ? 'published' : 'draft',
            ]);
        }

        // 2. Trim & Normalize
        $this->merge([
            'title' => Str::upper(trim($this->title ?? '')), // Example normalization, actually just trim is better
        ]);

        if ($this->title) {
            $this->merge(['title' => trim($this->title)]);
        }

        if ($this->embed_code) {
            // Basic URL normalization if needed, but validation will catch it
            $this->merge(['embed_code' => trim($this->embed_code)]);
        }

        if ($this->thumbnail_url) {
            $this->merge(['thumbnail_url' => trim($this->thumbnail_url)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 1. Title
            'title' => ['required', 'string', 'min:3', 'max:255'],

            // 2. Description
            'description' => ['nullable', 'string', 'max:5000'],

            // 3. Type
            'type' => ['required', 'in:video,audio'],

            // 4. Video URL (embed_code field)
            'embed_code' => [
                'nullable', // Nullable if audio
                Rule::requiredIf(fn() => $this->type === 'video'),
                'url',
                'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be|vimeo\.com)\/.+$/i',
            ],

            // 5. Thumbnail Upload
            'thumbnail' => [
                'nullable',
                'required_without:thumbnail_url', // Ensure at least one thumbnail source
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072', // 3MB
            ],

            // 6. External Thumbnail URL
            'thumbnail_url' => [
                'nullable',
                // required_without check is handled by 'thumbnail' field to avoid duplicate error messages
                'url',
                'regex:/\.(jpg|jpeg|png|webp)(\?.*)?$/i', // Must look like an image
            ],

            // 7. Status
            'status' => ['required', 'in:draft,published'],

            // 8. Published At
            'published_at' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($this->status === 'published' && $value && now()->diffInDays($value, false) < 0) {
                        // "must not be before today" - strictly checks if date is in the past (days)
                        // However, allowing "now" means we should probably check if it's strictly older than start of today?
                        // "must not be before today" implies >= today 00:00:00
                        if (\Carbon\Carbon::parse($value)->lt(now()->startOfDay())) {
                            $fail('تاريخ النشر لا يمكن أن يكون في الماضي عند النشر.');
                        }
                    }
                },
            ],

            // 9. Playlists
            'playlists' => ['nullable', 'array'],
            'playlists.*' => ['exists:vod_playlists,id'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Feature Flag Enforcement
            if ($this->type === 'video' && !config('features.vod.video')) {
                $validator->errors()->add('type', 'خاصية الفيديو غير مفعلة حالياً.');
            }

            if ($this->type === 'audio' && !config('features.vod.audio')) {
                $validator->errors()->add('type', 'خاصية الصوت غير مفعلة حالياً.');
            }
        });
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'type' => 'نوع المحتوى',
            'embed_code' => 'رابط الفيديو',
            'thumbnail' => 'ملف الصورة المصغرة',
            'thumbnail_url' => 'رابط الصورة الخارجية',
            'published_at' => 'تاريخ النشر',
            'status' => 'الحالة',
            'playlists' => 'قوائم التشغيل',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'يرجى إدخال عنوان المحتوى.',
            'title.min' => 'يجب أن يحتوي العنوان على 3 أحرف على الأقل.',
            'type.in' => 'النوع المختار غير صالح.',
            'embed_code.required_if' => 'رابط الفيديو مطلوب لنوع المحتوى فيديو.',
            'embed_code.url' => 'يجب أن يكون رابط الفيديو رابطاً صحيحاً.',
            'embed_code.regex' => 'يجب أن يكون الرابط من YouTube أو Vimeo فقط.',
            'thumbnail.required_without' => 'يجب إضافة صورة مصغرة (ملف أو رابط).',
            // 'thumbnail_url.required_without' => 'يجب إضافة صورة مصغرة (ملف أو رابط).', // Removed to avoid duplicate
            'thumbnail.max' => 'حجم الصورة يجب ألا يتجاوز 3 ميجابايت.',
            'thumbnail_url.regex' => 'رابط الصورة يجب أن يؤدي لملف صورة (jpg, png, webp).',
        ];
    }
}
