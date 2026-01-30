    {{-- Google Search Console Verification --}}
    <meta name="google-site-verification" content="dsFJffJ4xhPZeS16nSG0npAk5pKhQVwDd9KTCPn2N34" />

    {{-- Smart SEO Meta Tags - Auto-detect Post Pages for Social Sharing --}}
    @php
        use Illuminate\Support\Str;
        
        // ============================================================
        // BRANDING CONFIG - All values from config/branding.php
        // ============================================================
        $siteDomain = config('branding.site_domain');
        
        // Detect if we're on a single post page (error-proof check)
        $isPostPage = isset($post) && Route::currentRouteName() === 'post.show';
        
        // Site defaults from branding config
        $siteName = config('branding.site_name');
        $defaultImage = $siteDomain . '/' . config('branding.default_og_image');
        $defaultTitle = config('branding.site_name') . ' | ' . config('branding.tagline');
        $defaultDescription = config('branding.site_description');
        $defaultKeywords = config('branding.default_keywords');
        
        // Dynamic SEO values based on page type
        if ($isPostPage) {
            // Single Post Page - Use post data
            $seoTitle = $post->title;
            $seoDescription = $post->excerpt ?? Str::limit(strip_tags($post->content), 160);
            $seoKeywords = $post->tags->pluck('name')->implode(', ') ?: $defaultKeywords;
            
            // Image: HARDCODED DOMAIN for WhatsApp/Facebook compatibility
            $mimeType = 'image/jpeg'; // Default MIME type
            
            if ($post->featured_image_path) {
                // Clean the path - remove leading slashes and 'storage/' prefix if exists
                $cleanPath = ltrim($post->featured_image_path, '/');
                $cleanPath = Str::startsWith($cleanPath, 'storage/') 
                    ? $cleanPath 
                    : 'storage/' . $cleanPath;
                $seoImage = $siteDomain . '/' . $cleanPath;
                
                // Dynamic MIME Type Detection
                $ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                if ($ext === 'png') $mimeType = 'image/png';
                if ($ext === 'webp') $mimeType = 'image/webp';
            } else {
                $seoImage = $defaultImage;
            }
            
            $ogType = 'article';
            $ogTitle = $post->title;
            $ogDescription = $post->excerpt ?? Str::limit(strip_tags($post->content), 200);
            $twitterTitle = $post->title . ' | ' . config('branding.site_name');
            $twitterDescription = $post->excerpt ?? Str::limit(strip_tags($post->content), 200);
        } else {
            // Non-Post Pages - Use defaults (can be overridden via @section)
            $seoTitle = $defaultTitle;
            $seoDescription = $defaultDescription;
            $seoKeywords = $defaultKeywords;
            $seoImage = $defaultImage;
            $mimeType = 'image/jpeg';
            $ogType = 'website';
            $ogTitle = config('branding.site_name') . ' - ' . config('branding.tagline');
            $ogDescription = config('branding.site_description');
            $twitterTitle = config('branding.site_name') . ' | ' . config('branding.author.title');
            $twitterDescription = config('branding.site_description');
        }
        
        // Current URL - also hardcoded domain
        $currentUrl = $siteDomain . '/' . ltrim(request()->path(), '/');
    @endphp

    <!-- DEBUG OG:IMAGE URL: {{ $seoImage }} -->

    <meta name="title" content="@yield('title', $seoTitle)">
    <meta name="description" content="@yield('description', $seoDescription)">
    <meta name="keywords" content="@yield('keywords', $seoKeywords)">
    <meta name="author" content="{{ config('branding.author.name') }}">

    {{-- Canonical URL (Prevents Duplicate Content) --}}
    <link rel="canonical" href="{{ $currentUrl }}">

    {{-- Open Graph / Facebook / LinkedIn / WhatsApp --}}
    <meta property="og:type" content="@yield('og_type', $ogType)">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:title" content="@yield('og_title', $ogTitle)">
    <meta property="og:description" content="@yield('og_description', $ogDescription)">
    <meta property="og:image" content="@yield('og_image', $seoImage)">
    <meta property="og:image:secure_url" content="@yield('og_image', $seoImage)">
    <meta property="og:image:type" content="@yield('og_image_type', $mimeType)">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $isPostPage ? $post->title : config('branding.site_name') . ' - ' . config('branding.author.title') }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:locale:alternate" content="ar_AR">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ config('branding.social.twitter_handle') }}">
    <meta name="twitter:creator" content="{{ config('branding.social.twitter_handle') }}">
    <meta name="twitter:url" content="{{ $currentUrl }}">
    <meta name="twitter:title" content="@yield('twitter_title', $twitterTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $twitterDescription)">
    <meta name="twitter:image" content="@yield('og_image', $seoImage)">
    <meta name="twitter:image:alt" content="{{ $isPostPage ? $post->title : config('branding.site_name') . ' - ' . config('branding.author.title') }}">
