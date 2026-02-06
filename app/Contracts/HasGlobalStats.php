<?php

namespace App\Contracts;

/**
 * Contract for modules/services that provide global statistics.
 * 
 * Implementing classes can expose counters that will be merged
 * into layout data via GlobalStatsManager.
 * 
 * @see \App\Support\GlobalStats\GlobalStatsManager
 */
interface HasGlobalStats
{
    /**
     * Return associative array of global counters.
     * 
     * Keys should match expected Blade variable names.
     * Values should be integers or booleans.
     * 
     * Example:
     * [
     *     'unreadMessagesCount' => 5,
     *     'pendingOrders' => 2,
     *     'hasNewNotifications' => true,
     * ]
     *
     * @return array<string, int|bool>
     */
    public function getGlobalStats(): array;
}
