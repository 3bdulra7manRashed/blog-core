<?php

/**
 * Branding Configuration
 * 
 * This file centralizes all site branding settings to allow easy customization
 * for different deployments without modifying view files or hardcoded values.
 * 
 * All values can be overridden via .env file.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Site Name
    |--------------------------------------------------------------------------
    |
    | The main name of the website displayed in the header, footer, and meta tags.
    |
    */
    'site_name' => env('BRANDING_SITE_NAME', 'My Blog'),

    /*
    |--------------------------------------------------------------------------
    | Site Tagline
    |--------------------------------------------------------------------------
    |
    | A short tagline or slogan for the website.
    |
    */
    'tagline' => env('BRANDING_TAGLINE', 'Content Platform'),

    /*
    |--------------------------------------------------------------------------
    | Site Description
    |--------------------------------------------------------------------------
    |
    | Default meta description for SEO purposes.
    |
    */
    'site_description' => env('BRANDING_SITE_DESCRIPTION', 'A modern content platform for sharing ideas and articles.'),

    /*
    |--------------------------------------------------------------------------
    | Site Domain
    |--------------------------------------------------------------------------
    |
    | The main domain of the website (without trailing slash).
    | Used for SEO meta tags and canonical URLs.
    |
    */
    'site_domain' => env('BRANDING_SITE_DOMAIN', env('APP_URL', 'https://example.com')),

    /*
    |--------------------------------------------------------------------------
    | Author Information
    |--------------------------------------------------------------------------
    |
    | Default author information for meta tags and schema.org data.
    |
    */
    'author' => [
        'name' => env('BRANDING_AUTHOR_NAME', 'Site Author'),
        'name_en' => env('BRANDING_AUTHOR_NAME_EN', 'Site Author'),
        'title' => env('BRANDING_AUTHOR_TITLE', 'Content Creator'),
        'bio' => env('BRANDING_AUTHOR_BIO', 'Content creator and blogger.'),
        'avatar' => env('BRANDING_AUTHOR_AVATAR', 'images/avatar.jpg'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Links
    |--------------------------------------------------------------------------
    |
    | Social media profile URLs for the site/author.
    |
    */
    'social' => [
        'twitter' => env('BRANDING_SOCIAL_TWITTER', ''),
        'twitter_handle' => env('BRANDING_SOCIAL_TWITTER_HANDLE', ''),
        'linkedin' => env('BRANDING_SOCIAL_LINKEDIN', ''),
        'facebook' => env('BRANDING_SOCIAL_FACEBOOK', ''),
        'instagram' => env('BRANDING_SOCIAL_INSTAGRAM', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default SEO Keywords
    |--------------------------------------------------------------------------
    |
    | Default keywords for SEO meta tags.
    |
    */
    'default_keywords' => env('BRANDING_DEFAULT_KEYWORDS', 'blog, articles, content'),

    /*
    |--------------------------------------------------------------------------
    | Default OG Image
    |--------------------------------------------------------------------------
    |
    | Path to the default Open Graph image relative to public directory.
    | Used for social media sharing when no specific image is available.
    |
    */
    'default_og_image' => env('BRANDING_DEFAULT_OG_IMAGE', 'images/og-default.jpg'),

    /*
    |--------------------------------------------------------------------------
    | Newsletter Branding
    |--------------------------------------------------------------------------
    |
    | Branding for newsletter emails.
    |
    */
    'newsletter' => [
        'from_address' => env('NEWSLETTER_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'newsletter@example.com')),
        'from_name' => env('NEWSLETTER_MAIL_FROM_NAME', env('BRANDING_SITE_NAME', 'My Blog')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Blog Identity Mode
    |--------------------------------------------------------------------------
    |
    | Controls how post authorship is displayed.
    | 'single' = All posts shown as Site Owner.
    | 'multi' = Posts shown with actual author.
    |
    */
    'blog_identity_mode' => env('BLOG_IDENTITY_MODE', 'single'),

];
