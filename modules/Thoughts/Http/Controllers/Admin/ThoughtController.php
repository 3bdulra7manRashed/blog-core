<?php

namespace Modules\Thoughts\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Thoughts\Http\Requests\Admin\CreateThoughtRequest;
use Modules\Thoughts\Http\Requests\Admin\UpdateThoughtRequest;
use Modules\Thoughts\Models\Thought;

class ThoughtController extends Controller
{
    /**
     * Display a listing of thoughts.
     */
    public function index(Request $request): View
    {
        $query = Thought::query();

        // Filter by status
        if ($request->has('status')) {
            match ($request->status) {
                'draft' => $query->where('is_published', false),
                'published' => $query->published(),
                default => null,
            };
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $thoughts = $query->ordered()->paginate(15)->withQueryString();

        return view('thoughts::admin.thoughts.index', compact('thoughts'));
    }

    /**
     * Show the form for creating a new thought.
     */
    public function create(): View
    {
        return view('thoughts::admin.thoughts.create');
    }

    /**
     * Store a newly created thought.
     */
    public function store(CreateThoughtRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle publish/draft action
        $action = $request->input('action');
        if ($action === 'publish') {
            $data['is_published'] = true;
            $data['published_at'] = $request->filled('published_at')
                ? $request->published_at
                : now();
        } elseif ($action === 'draft') {
            $data['is_published'] = false;
            $data['published_at'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('thoughts', 'public');
            $data['image'] = $path;
        }

        // Default sort_order
        $data['sort_order'] = $data['sort_order'] ?? 0;

        // Handle is_featured checkbox
        $data['is_featured'] = $request->has('is_featured');

        Thought::create($data);

        return redirect()
            ->route('admin.thoughts.index')
            ->with('success', 'تم إنشاء الخاطرة بنجاح');
    }

    /**
     * Show the form for editing a thought.
     */
    public function edit(Thought $thought): View
    {
        return view('thoughts::admin.thoughts.edit', compact('thought'));
    }

    /**
     * Update the specified thought.
     */
    public function update(UpdateThoughtRequest $request, Thought $thought): RedirectResponse
    {
        $data = $request->validated();

        // Handle publish/draft action
        $action = $request->input('action');
        if ($action === 'publish') {
            $data['is_published'] = true;
            $data['published_at'] = $request->filled('published_at')
                ? $request->published_at
                : now();
        } elseif ($action === 'draft') {
            $data['is_published'] = false;
            $data['published_at'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($thought->image && Storage::disk('public')->exists($thought->image)) {
                Storage::disk('public')->delete($thought->image);
            }
            $path = $request->file('image')->store('thoughts', 'public');
            $data['image'] = $path;
        }

        // Handle is_featured checkbox
        $data['is_featured'] = $request->has('is_featured');

        $thought->update($data);

        return redirect()
            ->route('admin.thoughts.index')
            ->with('success', 'تم تحديث الخاطرة بنجاح');
    }

    /**
     * Remove the specified thought.
     */
    public function destroy(Thought $thought): RedirectResponse
    {
        // Delete image if exists
        if ($thought->image && Storage::disk('public')->exists($thought->image)) {
            Storage::disk('public')->delete($thought->image);
        }

        $thought->delete();

        return redirect()
            ->route('admin.thoughts.index')
            ->with('success', 'تم حذف الخاطرة بنجاح');
    }
}
