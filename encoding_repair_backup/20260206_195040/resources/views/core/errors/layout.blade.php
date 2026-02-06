{{-- Core Fallback Error Layout --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') - {{ config('branding.site_name') }}</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            direction: rtl;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .error-message {
            color: #666;
            margin: 1rem 0 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">@yield('code', '!')</h1>
        <p class="error-message">@yield('message', 'An error occurred.')</p>
        <a href="{{ url('/') }}" class="btn">Return Home</a>
    </div>
</body>
</html>
