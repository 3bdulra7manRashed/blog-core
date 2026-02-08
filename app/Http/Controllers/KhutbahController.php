<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\SEO\SeoManager;
use Illuminate\View\View;

class KhutbahController extends Controller
{
    /**
     * Display a listing of khutab.
     */
    public function index(): View
    {
        $posts = Post::with(['author', 'categories', 'tags'])
            ->khutab()
            ->published()
            ->latest('published_at')
            ->paginate(9);

        $recentPosts = Post::khutab()
            ->published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('order_column')
            ->get();

        return view('khutab.index', compact('posts', 'recentPosts', 'categories'));
    }

    /**
     * Display the specified khutbah.
     */
    public function show(string $slug, SeoManager $seoManager): View
    {
        $post = Post::with(['author', 'categories', 'tags'])
            ->khutab()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views count
        $post->increment('views');

        // Set SEO data via SeoManager (generates OpenGraph, Twitter, JSON-LD)
        $seoManager->forModel($post);

        $relatedPosts = Post::khutab()
            ->published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->limit(3)
            ->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::khutab()
                ->published()
                ->where('id', '!=', $post->id)
                ->limit(3)
                ->get();
        }

        $previousPost = Post::khutab()
            ->published()
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Post::khutab()
            ->published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $recentPosts = Post::khutab()
            ->published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('order_column')
            ->get();

        return view('khutab.show', compact('post', 'relatedPosts', 'previousPost', 'nextPost', 'recentPosts', 'categories'));
    }
}
