<?php

namespace Modules\Newsletter\Http\Controllers;

use Modules\Newsletter\Models\Subscriber;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter unsubscribe request.
     * 
     * This endpoint is accessed via the unsubscribe link in email footers.
     * It deactivates the subscriber rather than deleting them, allowing
     * for potential re-subscription in the future.
     */
    public function unsubscribe(string $token): View
    {
        // Find subscriber by unsubscribe token
        $subscriber = Subscriber::where('unsubscribe_token', $token)->first();

        // If no subscriber found with this token
        if (!$subscriber) {
            return view('newsletter.unsubscribed', [
                'success' => false,
                'message' => 'رابط إلغاء الاشتراك غير صالح أو منتهي الصلاحية.',
            ]);
        }

        // Check if already unsubscribed
        if (!$subscriber->is_active) {
            return view('newsletter.unsubscribed', [
                'success' => true,
                'message' => 'تم إلغاء اشتراكك مسبقاً.',
                'already_unsubscribed' => true,
            ]);
        }

        // Deactivate the subscriber (soft unsubscribe)
        $subscriber->update(['is_active' => false]);

        return view('newsletter.unsubscribed', [
            'success' => true,
            'message' => 'تم إلغاء اشتراكك بنجاح.',
        ]);
    }

    /**
     * Handle newsletter subscription.
     * 
     * Supports both AJAX (returns JSON) and traditional form submission (returns redirect).
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
        ]);

        // Check if subscriber exists (including soft deleted)
        $subscriber = Subscriber::withTrashed()
            ->where('email', $validated['email'])
            ->first();

        $message = 'تم الاشتراك في النشرة البريدية بنجاح! 🎉';
        
        if ($subscriber) {
            // Check if already active
            if ($subscriber->is_active && !$subscriber->trashed()) {
                $message = 'أنت مشترك بالفعل في النشرة البريدية.';
            } else {
                // Reactivate if exists
                $subscriber->restore();
                $subscriber->update(['is_active' => true]);
                $message = 'مرحباً بعودتك! تم تفعيل اشتراكك مجدداً.';
            }
        } else {
            // Create new subscriber
            Subscriber::create([
                'email' => $validated['email'],
                'is_active' => true,
            ]);
        }

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
