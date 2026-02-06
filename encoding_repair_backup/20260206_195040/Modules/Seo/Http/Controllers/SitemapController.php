<?php

namespace Modules\Seo\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Generate and return sitemap.xml dynamically
     */
    public function index(): Response
    {
        $urls = [];

        // 1. Static Pages
        $urls[] = $this->makeUrl(url('/'), '1.0', 'daily');

        if (Route::has('about')) {
            $urls[] = $this->makeUrl(route('about'), '0.8', 'monthly');
        }

        if (Route::has('contact')) {
            $urls[] = $this->makeUrl(route('contact'), '0.7', 'monthly');
        }

        // 2. Blog Posts (Core)
        if (class_exists(\App\Models\Post::class)) {
            try {
                $posts = \App\Models\Post::published()
                    ->orderBy('published_at', 'desc')
                    ->get();

                foreach ($posts as $post) {
                    if (Route::has('post.show')) {
                        $urls[] = $this->makeUrl(route('post.show', $post->slug), '0.9', 'weekly', $post->updated_at);
                    }
                }
            } catch (\Exception $e) {
                // Ignore if table/model issues
            }
        }

        // 3. Categories (Core)
        if (class_exists(\App\Models\Category::class)) {
             try {
                $categories = \App\Models\Category::withCount('posts')
                    ->having('posts_count', '>', 0)
                    ->get();

                foreach ($categories as $category) {
                     if (Route::has('category.show')) {
                        $urls[] = $this->makeUrl(route('category.show', $category->slug), '0.7', 'weekly', $category->updated_at);
                     }
                }
             } catch (\Exception $e) {}
        }

        // 4. Tags (Core)
        if (class_exists(\App\Models\Tag::class)) {
             try {
                $tags = \App\Models\Tag::withCount('posts')
                    ->having('posts_count', '>', 0)
                    ->get();

                foreach ($tags as $tag) {
                     if (Route::has('tag.show')) {
                        $urls[] = $this->makeUrl(route('tag.show', $tag->slug), '0.6', 'weekly', $tag->updated_at);
                     }
                }
             } catch (\Exception $e) {}
        }

        // 5. Listing pages
        if (Route::has('posts.most-liked')) {
            $urls[] = $this->makeUrl(route('posts.most-liked'), '0.8', 'daily');
        }

        if (Route::has('posts.most-read')) {
            $urls[] = $this->makeUrl(route('posts.most-read'), '0.8', 'daily');
        }

        // =========================================================================
        // 6. Advanced SEO: VOD Content (Videos, Audio, Playlists)
        // Only included when advanced_seo feature is enabled
        // =========================================================================
        if (feature('advanced_seo')) {
            $this->addVodContentToSitemap($urls);
        }

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Add VOD content (videos, audio, playlists) to sitemap
     *
     * @param array $urls Reference to URLs array
     */
    private function addVodContentToSitemap(array &$urls): void
    {
        // Videos
        if (feature('vod.video') && class_exists(\Modules\Vod\Models\VodContent::class)) {
            try {
                $videos = \Modules\Vod\Models\VodContent::published()
                    ->videos()
                    ->orderBy('published_at', 'desc')
                    ->get();

                foreach ($videos as $video) {
                    if (Route::has('videos.show')) {
                        $urls[] = $this->makeUrl(
                            route('videos.show', $video->slug),
                            '0.8',
                            'weekly',
                            $video->updated_at
                        );
                    }
                }
            } catch (\Exception $e) {}
        }

        // Audio
        if (feature('vod.audio') && class_exists(\Modules\Vod\Models\VodContent::class)) {
            try {
                $audios = \Modules\Vod\Models\VodContent::published()
                    ->audios()
                    ->orderBy('published_at', 'desc')
                    ->get();

                foreach ($audios as $audio) {
                    if (Route::has('audios.show')) {
                        $urls[] = $this->makeUrl(
                            route('audios.show', $audio->slug),
                            '0.7',
                            'weekly',
                            $audio->updated_at
                        );
                    }
                }
            } catch (\Exception $e) {}
        }

        // Playlists
        if (feature('vod.playlists') && class_exists(\Modules\Vod\Models\VodPlaylist::class)) {
            try {
                $playlists = \Modules\Vod\Models\VodPlaylist::orderBy('updated_at', 'desc')
                    ->get();

                foreach ($playlists as $playlist) {
                    $routeName = $playlist->type === 'video' ? 'videos.playlists.show' : 'audios.playlists.show';
                    if (Route::has($routeName)) {
                        $urls[] = $this->makeUrl(
                            route($routeName, $playlist->slug),
                            '0.6',
                            'weekly',
                            $playlist->updated_at
                        );
                    }
                }
            } catch (\Exception $e) {}
        }

        // Video library index
        if (feature('vod.video') && Route::has('videos.index')) {
            $urls[] = $this->makeUrl(route('videos.index'), '0.8', 'daily');
        }

        // Audio library index
        if (feature('vod.audio') && Route::has('audios.index')) {
            $urls[] = $this->makeUrl(route('audios.index'), '0.8', 'daily');
        }
    }

    private function makeUrl($loc, $priority, $changefreq, $lastmod = null)
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod ? $lastmod->toW3cString() : now()->toW3cString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }
}

