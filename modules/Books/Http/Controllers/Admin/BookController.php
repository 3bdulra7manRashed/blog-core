<?php

namespace Modules\Books\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Books\Http\Requests\Admin\CreateBookRequest;
use Modules\Books\Http\Requests\Admin\UpdateBookRequest;
use Modules\Books\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request): View
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('books::admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        return view('books::admin.books.create');
    }

    /**
     * Store a newly created book.
     */
    public function store(CreateBookRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateSlug($data['slug'] ?? $data['title']);

        // Handle publish/draft action
        if ($request->action === 'publish') {
            $data['status'] = 'published';
            $data['published_at'] = $request->published_at ?? now();
        } else {
            $data['status'] = 'draft';
            $data['published_at'] = null;
        }

        // Handle cover image upload or URL
        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('books', 'public');
            $data['cover_image'] = '/storage/' . $path;
        } elseif ($request->filled('cover_url')) {
            $data['cover_image'] = $request->cover_url;
        }

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'تم إنشاء الكتاب بنجاح');
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View
    {
        return view('books::admin.books.edit', compact('book'));
    }

    /**
     * Update the specified book.
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['slug']);
        } elseif (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        // Handle publish/draft action
        if ($request->action === 'publish') {
            $data['status'] = 'published';
            $data['published_at'] = $request->published_at ?? now();
        } else {
            $data['status'] = 'draft';
            $data['published_at'] = null;
        }

        // Handle cover image upload or URL
        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('books', 'public');
            $data['cover_image'] = '/storage/' . $path;
        } elseif ($request->filled('cover_url')) {
            $data['cover_image'] = $request->cover_url;
        }

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'تم تحديث الكتاب بنجاح');
    }

    /**
     * Remove the specified book (soft delete).
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'تم حذف الكتاب بنجاح');
    }

    /**
     * Generate a slug that preserves Arabic characters.
     */
    private function generateSlug(string $text): string
    {
        $slug = preg_replace('/\s+/', '-', trim($text));
        $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}\-]+/u', '', $slug);
        $slug = preg_replace('/\-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
