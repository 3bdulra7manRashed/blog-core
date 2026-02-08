<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KhutbahController extends Controller
{
    /**
     * Generate a slug that preserves Arabic characters
     */
    private function generateArabicSlug(string $text): string
    {
        $slug = preg_replace('/\s+/', '-', trim($text));
        $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}\-]+/u', '', $slug);
        $slug = preg_replace('/\-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Display a listing of khutab.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $query = Post::with(['author', 'categories', 'tags'])->khutab();

        if ($request->has('status')) {
            match ($request->status) {
                'draft' => $query->where('is_draft', true),
                'published' => $query->published(),
                default => null,
            };
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.khutab.index', compact('posts'));
    }

    /**
     * Show the form for creating a new khutbah.
     */
    public function create(): View
    {
        Gate::authorize('create', Post::class);

        $categories = Category::orderBy('order_column')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.khutab.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created khutbah.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->generateArabicSlug($data['slug'] ?? $data['title']);
        
        // Force type to khutbah
        $data['type'] = 'khutbah';

        // Handle publish/draft action from publish-card component
        $action = $request->input('action');
        if ($action === 'publish') {
            $data['is_draft'] = false;
            $data['published_at'] = $request->filled('published_at')
                ? $request->published_at
                : now();
        } elseif ($action === 'draft') {
            $data['is_draft'] = true;
            $data['published_at'] = null;
        } else {
            if (!empty($data['published_at']) && empty($data['is_draft'])) {
                $publishDate = \Carbon\Carbon::parse($data['published_at']);
                if ($publishDate->isPast() || $publishDate->isToday()) {
                    $data['is_draft'] = false;
                }
            }
        }

        if (isset($data['featured_image'])) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $data['featured_image_path'] = $path;
            unset($data['featured_image']);
        }

        $post = Post::create($data);

        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.khutab.index')
            ->with('success', 'تم إنشاء الخطبة بنجاح');
    }

    /**
     * Show the form for editing the specified khutbah.
     */
    public function edit(Post $khutbah): View
    {
        Gate::authorize('update', $khutbah);

        $khutbah->load(['categories', 'tags']);
        $categories = Category::orderBy('order_column')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.khutab.edit', [
            'post' => $khutbah,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified khutbah.
     */
    public function update(UpdatePostRequest $request, Post $khutbah): RedirectResponse
    {
        Gate::authorize('update', $khutbah);

        $data = $request->validated();

        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = $this->generateArabicSlug($data['slug']);
        } elseif (empty($data['slug'] ?? '')) {
            $data['slug'] = $this->generateArabicSlug($data['title']);
        }

        // DO NOT change type - keep existing type

        // Handle publish/draft action from publish-card component
        $action = $request->input('action');
        if ($action === 'publish') {
            $data['is_draft'] = false;
            $data['published_at'] = $request->filled('published_at')
                ? $request->published_at
                : now();
        } elseif ($action === 'draft') {
            $data['is_draft'] = true;
            $data['published_at'] = null;
        } else {
            if (!empty($data['published_at']) && empty($data['is_draft'])) {
                $publishDate = \Carbon\Carbon::parse($data['published_at']);
                if ($publishDate->isPast() || $publishDate->isToday()) {
                    $data['is_draft'] = false;
                }
            }
        }

        if ($request->hasFile('featured_image')) {
            if ($khutbah->featured_image_path) {
                Storage::disk('public')->delete($khutbah->featured_image_path);
            }
            $path = $request->file('featured_image')->store('posts', 'public');
            $data['featured_image_path'] = $path;
        }

        $khutbah->update($data);

        if (isset($data['categories'])) {
            $khutbah->categories()->sync($data['categories']);
        } else {
            $khutbah->categories()->detach();
        }

        if (isset($data['tags'])) {
            $khutbah->tags()->sync($data['tags']);
        } else {
            $khutbah->tags()->detach();
        }

        return redirect()->route('admin.khutab.index')
            ->with('success', 'تم تحديث الخطبة بنجاح');
    }

    /**
     * Remove the specified khutbah.
     */
    public function destroy(Post $khutbah): RedirectResponse
    {
        Gate::authorize('delete', $khutbah);

        if ($khutbah->featured_image_path) {
            Storage::disk('public')->delete($khutbah->featured_image_path);
        }

        $khutbah->delete();

        return redirect()->route('admin.khutab.index')
            ->with('success', 'تم حذف الخطبة بنجاح');
    }
}
