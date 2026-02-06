<?php

namespace App\View\Composers;

use App\Support\GlobalStats\GlobalStatsManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * View Composer for admin layout
 * 
 * Injects computed state variables into the admin layout.
 * This removes direct model/database queries from Blade templates.
 * 
 * Also merges stats from GlobalStatsManager for extensibility.
 */
class AdminLayoutComposer
{
    /**
     * Global stats manager instance.
     */
    protected GlobalStatsManager $statsManager;

    /**
     * Create a new composer instance.
     */
    public function __construct(GlobalStatsManager $statsManager)
    {
        $this->statsManager = $statsManager;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Core stats (existing behavior - unchanged)
        $coreStats = [
            'unreadMessagesCount' => $this->getUnreadMessagesCount(),
        ];

        // Merge additional stats from registered providers (extensibility)
        // This is additive and non-breaking. If no providers registered,
        // GlobalStatsManager returns empty array and merge has no effect.
        $additionalStats = $this->statsManager->resolve();

        // Merge: additional stats can override core stats if needed
        // But for backward compatibility, we let core stats take precedence
        // by merging in this order: additional first, then core
        $allStats = array_merge($additionalStats, $coreStats);

        $view->with($allStats);
    }

    /**
     * Get unread messages count from Contact module.
     * Uses caching to avoid repeated queries on every page load.
     */
    protected function getUnreadMessagesCount(): int
    {
        if (!function_exists('feature') || !feature('contact')) {
            return 0;
        }

        if (!class_exists(\Modules\Contact\Models\ContactMessage::class)) {
            return 0;
        }

        // Cache for 60 seconds to reduce DB hits while keeping relatively fresh
        return Cache::remember('admin.unread_messages_count', 60, function () {
            return \Modules\Contact\Models\ContactMessage::unreadCount();
        });
    }
}

