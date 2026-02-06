<?php

namespace Modules\Vod\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Vod\Models\VodPlaylist;
use Modules\Vod\Models\VodContent;

class VodPlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playlists = VodPlaylist::withCount('items')
            ->latest()
            ->paginate(10);

        return view('vod::admin.playlists.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch published contents for selection
        $contents = VodContent::where('status', 'published')->latest()->get();
        return view('vod::admin.playlists.create', compact('contents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,audio',
            'slug' => 'nullable|string|max:255|unique:vod_playlists,slug',
            'description' => 'nullable|string',
            'contents' => 'nullable|array',
            'contents.*' => 'exists:vod_contents,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        $validated['user_id'] = auth()->id();

        $playlist = VodPlaylist::create($validated);

        if ($request->has('contents')) {
            $playlist->items()->sync($request->input('contents'));
        }

        return redirect()->route('admin.vod.playlists.index')
            ->with('success', 'تم إنشاء القائمة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VodPlaylist $playlist)
    {
        $contents = VodContent::where('status', 'published')->latest()->get();
        $playlist->load('items');
        return view('vod::admin.playlists.edit', compact('playlist', 'contents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VodPlaylist $playlist)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,audio',
            'slug' => 'nullable|string|max:255|unique:vod_playlists,slug,' . $playlist->id,
            'description' => 'nullable|string',
            'contents' => 'nullable|array',
            'contents.*' => 'exists:vod_contents,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        $playlist->update($validated);

        if ($request->has('contents')) {
            $playlist->items()->sync($request->input('contents'));
        } else {
            $playlist->items()->detach();
        }

        return redirect()->route('admin.vod.playlists.index')
            ->with('success', 'تم تحديث القائمة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VodPlaylist $playlist)
    {
        $playlist->delete();
        return redirect()->route('admin.vod.playlists.index')
            ->with('success', 'تم حذف القائمة بنجاح');
    }
}
