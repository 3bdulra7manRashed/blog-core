<?php

namespace Modules\Vod\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\SEO\SeoManager;
use Modules\Vod\Models\VodContent;
use Modules\Vod\Models\VodPlaylist;
use Illuminate\Http\Request;

class VodController extends Controller
{
    public function indexVideos(Request $request)
    {
        $tab = $request->get('tab', 'videos');

        // Prevent accessing playlists if feature disabled
        if ($tab === 'playlists' && !config('features.vod.playlists')) {
            abort(404);
        }

        if ($tab === 'playlists') {
            $contents = VodPlaylist::where('type', 'video')
                ->withCount('items')
                ->with(['items' => function ($query) {
                    $query->where('status', 'published')->select('vod_contents.id', 'thumbnail_path')->limit(1);
                }])
                ->latest()
                ->paginate(12);

            $contents->appends(['tab' => 'playlists']);
        } else {
            $contents = VodContent::published()
                ->videos()
                ->latest('published_at')
                ->paginate(12);
        }

        return view('vod.front.index', [
            'contents' => $contents,
            'title' => 'مكتبة الفيديو',
            'type' => 'video',
            'currentTab' => $tab
        ]);
    }

    public function indexAudios(Request $request)
    {
        $tab = $request->get('tab', 'audios');

        // Prevent accessing playlists if feature disabled
        if ($tab === 'playlists' && !config('features.vod.playlists')) {
            abort(404);
        }

        if ($tab === 'playlists') {
            $contents = VodPlaylist::where('type', 'audio')
                ->withCount('items')
                ->with(['items' => function ($query) {
                    $query->where('status', 'published')->select('vod_contents.id', 'thumbnail_path')->limit(1);
                }])
                ->latest()
                ->paginate(12);

            $contents->appends(['tab' => 'playlists']);
        } else {
            $contents = VodContent::published()
                ->audios()
                ->latest('published_at')
                ->paginate(12);
        }

        return view('vod.front.index', [
            'contents' => $contents,
            'title' => 'مكتبة الصوتيات',
            'type' => 'audio',
            'currentTab' => $tab
        ]);
    }

    public function show(Request $request, $slug, SeoManager $seoManager)
    {
        $content = VodContent::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Strict Type Check (SEO & UX)
        if ($request->routeIs('videos.show') && $content->type !== 'video') {
            return redirect()->route('audios.show', $content->slug, 301);
        }
        if ($request->routeIs('audios.show') && $content->type !== 'audio') {
            return redirect()->route('videos.show', $content->slug, 301);
        }

        $content->increment('views_count');

        // Set SEO data via SeoManager (generates OpenGraph, Twitter, VideoObject/AudioObject JSON-LD)
        $seoManager->forModel($content);

        return view('vod.front.show', compact('content'));
    }

    public function showPlaylist($slug)
    {
        $playlist = VodPlaylist::where('slug', $slug)->firstOrFail();

        $playlist->load([
            'items' => function ($query) {
                $query->where('status', 'published');
            }
        ]);

        // Explicitly set type based on playlist type used for breadcrumbs/layout
        $type = $playlist->type;

        return view('vod.front.playlists.show', compact('playlist', 'type'));
    }
}
