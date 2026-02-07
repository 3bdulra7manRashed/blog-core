@props([
    'publishedAt' => null,
    'modelType' => 'post',
])

<div class="bg-white p-4 rounded shadow space-y-4">
    <h3 class="font-bold text-gray-800 border-b pb-2">النشر</h3>

    <div>
        <label class="block text-sm text-gray-600 mb-1">تاريخ النشر</label>
        <input type="datetime-local"
               name="published_at"
               value="{{ old('published_at', $publishedAt ? \Carbon\Carbon::parse($publishedAt)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
               class="w-full border rounded px-3 py-2 text-sm"
               dir="ltr">
    </div>

    <div class="flex justify-between pt-2 border-t">
        <button type="submit"
                name="action"
                value="draft"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">
            حفظ كمسودة
        </button>

        <button type="submit"
                name="action"
                value="publish"
                class="px-6 py-2 bg-black text-white rounded text-sm">
            نشر الآن
        </button>
    </div>
</div>
