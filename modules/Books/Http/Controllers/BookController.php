<?php

namespace Modules\Books\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\SEO\SeoManager;
use Illuminate\View\View;
use Modules\Books\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of published books.
     */
    public function index(): View
    {
        $books = Book::published()
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('books::front.index', compact('books'));
    }

    /**
     * Display the specified published book.
     */
    public function show(string $slug, SeoManager $seoManager): View
    {
        $book = Book::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $seoManager->forModel($book);

        return view('books::front.show', compact('book'));
    }
}
