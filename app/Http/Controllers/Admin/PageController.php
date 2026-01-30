<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function edit()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('pages', 'public');
            $page->featured_image = $path;
        }

        $page->title = $validated['title'];
        $page->content = $validated['content'];
        $page->save();

        return redirect()->route('admin.about.edit')->with('success', 'تم تحديث صفحة "عن المدونة" بنجاح');
    }
}
