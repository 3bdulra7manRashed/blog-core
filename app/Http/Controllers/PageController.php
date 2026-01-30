<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $page = \App\Models\Page::where('slug', 'about')->firstOrFail();

        // Keep passing these if used by layout (though about.blade.php doesn't seem to use them directly, layout might)
        $recentPosts = Post::published()->latest('published_at')->limit(5)->get();
        $categories = Category::withCount('posts')->orderBy('order_column')->get();
        
        // Pass user for layout compatibility if needed (e.g. footer), but page content now comes from Page model
        $user = \App\Support\PublishingIdentityResolver::forPost(new Post());

        return view('pages.about', compact('page', 'recentPosts', 'categories', 'user'));
    }
}
