# Theme System

This Laravel application uses a theme-based architecture to separate UI from core logic.

## Directory Structure

```
resources/
├── themes/
│   └── classic/              # The "classic" theme
│       ├── views/            # Blade templates
│       │   ├── auth/         # Authentication views
│       │   ├── components/   # Blade components
│       │   ├── errors/       # Error pages (404, 500, etc.)
│       │   ├── layouts/      # Layout templates
│       │   ├── newsletter/   # Newsletter views
│       │   ├── pages/        # Static pages (about, contact)
│       │   ├── partials/     # Reusable partials
│       │   ├── posts/        # Blog post views
│       │   └── profile/      # User profile views
│       └── assets/           # Theme-specific assets (CSS, JS, images)
│
└── views/
    ├── admin/                # Admin panel (not themed)
    ├── core/                 # Core fallback views
    │   ├── errors/           # Minimal error fallback
    │   └── layouts/          # Minimal layout fallback
    ├── emails/               # Email templates (not themed)
    └── vendor/               # Third-party views
```

## Configuration

The active theme is configured in `config/theme.php`:

```php
return [
    'active' => env('THEME_ACTIVE', 'classic'),
    'directory' => 'themes',
    'fallback_to_core' => true,
    'core_directory' => 'views/core',
];
```

You can change the active theme via the `.env` file:

```env
THEME_ACTIVE=classic
```

## How View Resolution Works

1. When a view is requested (e.g., `view('layouts.blog')`), Laravel first checks the **active theme** directory:
   - `resources/themes/classic/views/layouts/blog.blade.php`

2. If not found and `fallback_to_core` is enabled, it checks the **core views**:
   - `resources/views/core/layouts/blog.blade.php`

3. Finally, it falls back to the standard Laravel views directory.

## Helper Functions

### `theme_name()`
Returns the name of the active theme.

```php
echo theme_name(); // "classic"
```

### `theme_path($path = '')`
Returns the absolute path to the theme directory.

```php
echo theme_path(); // "/path/to/resources/themes/classic"
echo theme_path('views/layouts'); // "/path/to/resources/themes/classic/views/layouts"
```

### `theme_asset($path)`
Returns the URL to a theme asset.

```php
echo theme_asset('css/custom.css'); // Resolves to themed or default asset
```

### `theme_view_exists($view)`
Checks if a view exists in the current theme.

```php
if (theme_view_exists('layouts.blog')) {
    // Theme has this view
}
```

## Creating a New Theme

1. Create a new theme directory:
   ```
   resources/themes/my-new-theme/
   ├── views/
   └── assets/
   ```

2. Copy the views from an existing theme or create new ones.

3. Update `.env`:
   ```env
   THEME_ACTIVE=my-new-theme
   ```

4. Clear caches:
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

## Non-Themed Views

The following are NOT part of the theme system and remain in `resources/views/`:

- **admin/** - Admin panel views (separate admin UI)
- **emails/** - Email templates (transactional emails)
- **vendor/** - Third-party package views

## Blade Components

Theme components are automatically registered. Use them as usual:

```blade
<x-newsletter-form />
<x-sidebar-trending />
```

Components are resolved from the active theme's `views/components/` directory.

## Extending Layouts in Theme Views

Within theme views, layouts are resolved from the theme first:

```blade
@extends('layouts.blog')  {{-- Resolves to theme/views/layouts/blog.blade.php --}}
```

No changes to existing `@extends()` calls are required.
