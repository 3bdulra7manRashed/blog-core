<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $recentPosts = Post::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('order_column')
            ->get();

        // Fetch the site owner (super admin or user ID 1)
        $user = \App\Models\User::where('is_super_admin', true)->orWhere('id', 1)->first();

        return view('pages.about', compact('recentPosts', 'categories', 'user'));
    }
}
