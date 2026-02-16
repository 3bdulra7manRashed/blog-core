<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */

    'timezone' => 'Asia/Riyadh',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\CKEditorServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        // App\Providers\EventServiceProvider::class,
        // App\Providers\RouteServiceProvider::class, // Laravel 11 handles routes via bootstrap/app.php usually

        /*
         * Module Service Providers...
         */
        Modules\Newsletter\Providers\NewsletterServiceProvider::class,
        Modules\Contact\Providers\ContactServiceProvider::class,
        Modules\Media\Providers\MediaServiceProvider::class,
        Modules\Vod\Providers\VodServiceProvider::class,
        Modules\Download\Providers\DownloadServiceProvider::class,
        Modules\Seo\Providers\SeoServiceProvider::class,
        Modules\AdvancedSeo\Providers\AdvancedSeoServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Custom Configuration
    |--------------------------------------------------------------------------
    */

    'deleted_user_email' => env('DELETED_USER_EMAIL', 'deleted-user@local'),

    // Admin seeder credentials (used by AdminUserSeeder)
    'admin_name' => env('ADMIN_NAME', 'Saleh Alshehri'),
    'admin_email' => env('ADMIN_EMAIL', 'admin@alshehri.com'),
    'admin_password' => env('ADMIN_PASSWORD', 'password'),

];
