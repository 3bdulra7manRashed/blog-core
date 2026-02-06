<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/**
 * View Composer for public layout (blog.blade.php)
 * 
 * Injects computed state variables into the public layout.
 * This removes direct model/database queries from Blade templates.
 */
class PublicLayoutComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Check if Books feature is enabled and has published books
        $hasBooks = $this->checkHasPublishedBooks();
        
        // Determine if current page is a single post page (for layout conditionals)
        $isPostPage = request()->routeIs('post.show');
        
        $view->with([
            'hasBooks' => $hasBooks,
            'isPostPage' => $isPostPage,
        ]);
    }

    /**
     * Check if there are published books.
     * Uses caching to avoid repeated queries.
     */
    protected function checkHasPublishedBooks(): bool
    {
        if (!function_exists('feature') || !feature('books')) {
            return false;
        }

        if (!class_exists(\Modules\Books\Models\Book::class)) {
            return false;
        }

        return Cache::remember('layout.has_published_books', 300, function () {
            return \Modules\Books\Models\Book::published()->exists();
        });
    }
}
