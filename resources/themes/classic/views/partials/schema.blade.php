@php
    // Build social links array dynamically from config
    $socialLinks = array_filter([
        config('branding.social.twitter'),
        config('branding.social.linkedin'),
        config('branding.social.facebook'),
        config('branding.social.instagram'),
    ]);

    $personSchema = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "@id" => url('/') . "/#person",
        "name" => config('branding.author.name'),
        "alternateName" => config('branding.author.name_en'),
        "jobTitle" => config('branding.author.title'),
        "description" => config('branding.author.bio'),
        "url" => url('/'),
        "image" => asset(config('branding.default_og_image')),
        "sameAs" => array_values($socialLinks),
    ];

    $websiteSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "@id" => url('/') . "/#website",
        "name" => config('branding.site_name'),
        "alternateName" => config('branding.site_name'),
        "description" => config('branding.site_description'),
        "url" => url('/'),
        "inLanguage" => "ar",
        "publisher" => [
            "@id" => url('/') . "/#person"
        ],
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => [
                "@type" => "EntryPoint",
                "urlTemplate" => route('search') . "?q={search_term_string}"
            ],
            "query-input" => "required name=search_term_string"
        ]
    ];
@endphp

{{-- Global Person Schema --}}
<script type="application/ld+json">
    {!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- WebSite Schema --}}
<script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

