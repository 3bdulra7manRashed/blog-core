<?php

namespace Modules\Vod\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Vod\Models\VodContent;
use Modules\Vod\Http\Requests\StoreVodContentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VodContentController extends Controller
{
    public function index(Request $request)
    {
        // Extra Protection: Ensure at least one content type is enabled
        if (!config('features.vod.video') && !config('features.vod.audio')) {
            abort(404);
        }

        $query = VodContent::query()->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $contents = $query->paginate(20)->withQueryString();

        return view('admin.vod.contents.index', compact('contents'));
    }

    public function create()
    {
        return view('admin.vod.contents.create');
    }

    public function store(StoreVodContentRequest $request)
    {
        $data = $request->validated();

        // Handle Thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('vod/thumbnails', 'public');
        }

        // Handle Publishing Status strictly based on Action
        if ($request->action === 'publish') {
            $data['status'] = 'published';
            $data['published_at'] = now();
        } else {
            $data['status'] = 'draft';
            $data['published_at'] = null;
        }

        DB::transaction(function () use ($data) {
            // Generate Unique Slug (Arabic Safe)
            $slug = trim($data['title']);
            $slug = preg_replace('/\s+/u', '-', $slug);
            $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}\-]+/u', '', $slug);
            $slug = preg_replace('/-+/u', '-', $slug);
            $slug = trim($slug, '-');

            if (empty($slug)) {
                $slug = 'video-' . Str::random(6);
            }

            $originalSlug = $slug;
            $count = 1;

            // Check uniqueness considering soft deletes
            while (VodContent::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $data['slug'] = $slug;
            $data['user_id'] = auth()->id();

            VodContent::create($data);
        });

        return redirect()->route('admin.vod.contents.index')
            ->with('success', 'تم إضافة المحتوى بنجاح');
    }

    public function edit(VodContent $content)
    {
        return view('admin.vod.contents.edit', compact('content'));
    }

    public function update(StoreVodContentRequest $request, VodContent $content)
    {
        $data = $request->validated();

        // Handle Thumbnail
        if ($request->hasFile('thumbnail')) {
            // Delete old if exists
            if ($content->thumbnail_path && Storage::disk('public')->exists($content->thumbnail_path)) {
                Storage::disk('public')->delete($content->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('vod/thumbnails', 'public');
        }

        // Handle Publishing Status strictly based on Action
        if ($request->action === 'publish') {
            $data['status'] = 'published';
            $data['published_at'] = now();
        } elseif ($request->action === 'draft') {
            $data['status'] = 'draft';
            $data['published_at'] = null;
        }

        // Update Slug if title changed?  
        // Strategy: Only update slug if explicit slug field provided OR force update. 
        // For simplicity/SEO, we often keep slug unless manually changed. 
        // If we don't have slug input, we might want to update it if title changes significantly?
        // Let's Keep Slug stable for now to avoid broken links unless we really want to change it.
        // OR: $data['slug'] = Str::slug($data['title']) ...
        // I will keep slug unchanged in this iteration to be safe.
        unset($data['slug']);

        $content->update($data);

        return redirect()->route('admin.vod.contents.index')
            ->with('success', 'تم تحديث المحتوى بنجاح');
    }

    public function destroy(VodContent $content)
    {
        $content->delete();
        return redirect()->route('admin.vod.contents.index')
            ->with('success', 'تم حذف المحتوى بنجاح (نقل إلى السلة)');
    }
}
