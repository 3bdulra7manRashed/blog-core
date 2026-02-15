<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\Http\Controllers\NewsletterController;
use Modules\Newsletter\Http\Controllers\Admin\CampaignController;
use Modules\Newsletter\Http\Controllers\Admin\SubscriberController;

/*
|--------------------------------------------------------------------------
| Newsletter Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::middleware(['web', 'feature:newsletter'])->group(function () {
    Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
        ->middleware('throttle:5,1')
        ->name('newsletter.subscribe');
});

// Admin Routes
Route::middleware(['web', 'auth', 'role:admin|moderator', 'feature:newsletter'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Newsletter Campaigns Management
        Route::resource('campaigns', CampaignController::class);
        Route::post('campaigns/{campaign}/send-test', [CampaignController::class, 'sendTest'])->name('campaigns.send-test');
        Route::post('campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
        Route::get('campaigns/{campaign}/status', [CampaignController::class, 'status'])->name('campaigns.status');

        // Newsletter Subscribers Management
        Route::resource('subscribers', SubscriberController::class)->only(['index', 'store', 'destroy']);
        Route::patch('subscribers/{subscriber}/toggle', [SubscriberController::class, 'toggleStatus'])->name('subscribers.toggle');
    });
