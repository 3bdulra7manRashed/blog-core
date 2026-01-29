{{-- Core Fallback Layout --}}
{{-- This minimal layout is used when no theme layout is available --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('branding.site_name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; direction: rtl; }
        .container { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>{{ config('branding.site_name') }}</h1>
        </header>
        <main>
            @yield('content')
        </main>
        <footer>
            <p>&copy; {{ date('Y') }} {{ config('branding.site_name') }}</p>
        </footer>
    </div>
</body>
</html>
