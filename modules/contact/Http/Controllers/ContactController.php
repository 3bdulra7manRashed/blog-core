<?php

namespace Modules\Contact\Http\Controllers;

use App\Mail\ContactMessage as ContactMail;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Modules\Contact\Http\Requests\ContactFormRequest;
use Modules\Contact\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function show(): View
    {
        $recentPosts = Post::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('order_column')
            ->get();

        return view('contact.front.index', compact('recentPosts', 'categories'));
    }

    /**
     * Handle the contact form submission.
     */
    public function send(ContactFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            // Save message to database first
            $contactMessage = ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send email to the site owner
            Mail::to(config('mail.from.address'))->send(new ContactMail($validated));

            Log::info('Contact form submitted and saved', [
                'id' => $contactMessage->id,
                'from' => $validated['email'],
                'name' => $validated['name'],
            ]);

            return back()->with('success', 'شكراً لرسالتك. سيتم الرد عليك قريباً!');
        } catch (\Exception $e) {
            Log::error('Failed to process contact form', [
                'error' => $e->getMessage(),
                'from' => $validated['email'],
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى لاحقاً.');
        }
    }
}
