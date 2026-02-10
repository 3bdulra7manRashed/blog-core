# Blog Core System Architecture & Rules

> **Version**: 2.4.0
> **Status**: Authoritative Architectural Contract
> **Strictness**: High (Non-negotiable)

This document serves as the **Single Source of Truth** for the architecture of the Blog Core system. All AI agents and developers must adhere strictly to these rules.

---

## 1. PROJECT OVERVIEW

**Blog Core** is a modular, theme-aware Laravel application designed for high-performance content publishing.

### Key Characteristics
*   **Modular Architecture**: Domain logic is strictly encapsulated in `Modules/`. The `app/` directory is reserved for Core infrastructure and shared contracts.
*   **Feature-Flag Driven**: Features are strictly controlled via configuration contracts, allowing zero-code toggling.
*   **Theme-Aware**: The system uses a specialized Theme Engine to decouple logic from presentation. Hardcoding theme paths is prohibited.
*   **SEO-First**: SEO is a core service, not an afterthought.

---

## 2. CORE PRINCIPLES (NON-NEGOTIABLE)

1.  **Boundaries**: Modules MUST NOT depend on other modules directly. Use Events or Core Contracts for communication.
2.  **Isolation**: Code belonging to a domain (e.g., VOD) MUST reside in `Modules/{Name}`.
3.  **Feature Awareness**: Every optional feature MUST be guarded. No feature code should execute if the flag is disabled.
4.  **DRY & Reuse**: Use existing Services and Traits. Do not reinvent infrastructure.
5.  **Clean Architecture**: 
    *   **Controllers**: Map HTTP requests to Services. No business logic.
    *   **Services**: Handle business logic.
    *   **Models**: Handle data integrity and relationships.
6.  **Theme Independence**: Controllers must never know which theme is active.

---

## 3. SYSTEM ARCHITECTURE

### 3.1 Modules Structure (`Modules/`)
Each module is a self-contained unit with its own Routes, Views, Migrations, and Config.

| Module | Responsibility | Dependencies |
| :--- | :--- | :--- |
| **Media** | Core file library, uploads, image processing, storage abstraction. | Core |
| **Vod** | Video/Audio content, playlists, embed handling, streaming logic. | Media |
| **Seo** | Sitemap generation, analyzing tools, strict SEO logic. | Core |
| **Newsletter** | Subscriber management, campaigns, mailing integration. | Core |
| **Contact** | Contact forms, messaging, notifications. | Core |
| **Download** | Digital asset management and gated downloads. | Core |
| **AdvancedSeo** | *(Legacy)* Integration layer for specific advanced tags. | Core |
| **Landing** | Configurable landing page with hero section and dynamic content. | Core |
| **Thoughts** | Short-form content (quotes, thoughts) with admin CRUD management. | Core |

### 3.2 Theme Architecture
The system supports distinct themes for Public and Admin interfaces.
*   **Public Theme**: Controls the visitor experience.
*   **Admin Theme**: Controls the backend UI. Independently swappable.
*   **Resolution Rule**: NEVER use hardcoded paths like `resources/views/themes/x`. Use the Theme View Finder or generic namespaced views.
*   **Isolation**: Admin themes must implement the full Admin Contract (layout slots, components) to ensure compatibility.

### 3.3 Module Route Override Architecture

**Added**: 2026-02-08 | **Status**: Enforced

Modules may override core routes when their feature flags are enabled, following strict modular boundaries.

#### 3.3.1 Core Rules

| Rule | Description |
| :--- | :--- |
| **Core Independence** | Core routes (`routes/web.php`) MUST NOT reference `\Modules\*` namespaces directly |
| **Module Encapsulation** | Route override logic MUST reside entirely within the module's `Routes/web.php` |
| **Feature-Gated Override** | Module routes MUST check their feature flag before registering overrides |
| **Default Behavior** | Core routes define the default behavior; modules optionally override |

#### 3.3.2 Implementation

Module routes are loaded via `bootstrap/app.php`'s `then:` callback in `withRouting()`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    then: function () {
        foreach (glob(base_path('Modules/*/Routes/web.php')) as $routeFile) {
            require $routeFile;
        }
    },
)
```

This ensures:
1. Core routes load first (default behavior)
2. Module routes load after with override capability
3. Core has no knowledge of which modules exist

#### 3.3.3 Module Override Pattern

```php
// Modules/{Name}/Routes/web.php
if (feature('module_name')) {
    Route::get('/', [ModuleController::class, 'index'])
        ->name('module.home');
    
    // Relocate overridden route if needed
    Route::get('/original-path', [\App\Http\Controllers\CoreController::class, 'index'])
        ->name('original.name');
}
```

---

## 4. FEATURE FLAGS SYSTEM

**Configuration**: `config/features.php`

### 4.1 Naming Conventions
*   **Format**: `module.feature` (e.g., `vod.playlists`, `blog.comments`).
*   **Boolean**: Flags must resolve to strictly `true` or `false`.

### 4.2 Flags Inventory
*   **Core**:
    *   `seo` (Always True): Basic meta tags.
    *   `advanced_seo`: OpenGraph, JSON-LD, Robots.
*   **Modules**:
    *   `vod.enabled`: Master switch.
    *   `vod.video`, `vod.audio`, `vod.playlists`.
    *   `newsletter`, `download`.

### 4.3 Enforcement Layers
1.  **Routes**: Use `Route::middleware('feature:name')` or wrapping `if` blocks in route files.
2.  **UI**: Blade directives `@if(feature('name')) ... @endif`.
3.  **Services**: Early returns or null-object patterns if a feature is disabled.
4.  **SEO**: Automatically exclude disabled content types from Sitemaps and Meta tags.

---

## 5. DATABASE & MIGRATION RULES

### 5.1 Schema Standards
*   **Slug Uniqueness**: Unique per Model (Table). Global uniqueness is not required unless specified.
*   **Indexes**: Mandatory on `slug`, `published_at`, `status`, and Foreign Keys.
*   **Soft Deletes**: Mandatory for all primary content models.
*   **Status**: Use string enums (`published`, `draft`, `archived`).

### 5.2 Migration Ownership
*   **Core**: `database/migrations`
*   **Modules**: `Modules/{Name}/Database/Migrations`
*   **Rule**: A module MUST own its tables. Cross-module foreign keys are allowed but must use loose coupling (bigInteger ids) if possible.

---

## 6. ROUTING & CONTROLLERS

### 6.1 Routing Standard
*   **Naming**: `module.resource.action` (e.g., `vod.videos.show`).
*   **Public**: `Modules/{Name}/Routes/web.php`.
*   **Admin**: Prefix `admin/`, name prefix `admin.`. Middleware: `['web', 'auth', 'admin']`.

### 6.2 Controller Responsibility
*   **Orchestration**: Accept Request -> Validate input -> Call Service -> Return Response.
*   **Forbidden**:
    *   Raw SQL queries.
    *   Blade logic inside controllers.
    *   Direct generation of HTML/Meta tags (Use `SeoManager`).
*   **Response**: Must return View or JSON. No mixed types for the same endpoint.

---

## 7. SEO ARCHITECTURE

**Authority**: `App\Support\SEO\SeoManager`

### 7.1 Implementation
*   **Pattern**: Implementation of `Seoable` interface + `HasSeo` trait on Models.
*   **Integration**: Controller injects `SeoManager` and calls `forModel($item)`.
*   **Fallback**: If `advanced_seo` is off, `SeoManager` gracefully degrades to Basic tags.

### 7.2 Structured Data
*   **Logic**: Encapsulated within `SeoManager` methods (`buildArticleSchema`, `buildVideoSchema`).
*   **Constraint**: Never manually write JSON-LD in Blade views.

---

## 8. EXTENSIBILITY & COMMUNICATION

### 8.1 Adding New Modules
1.  Create directory structure.
2.  Register in `docs/blog-core-rules.md`.
3.  Add Feature Flag in `config/features.php`.
4.  Define `Seoable` models.

### 8.2 Cross-Module Communication
*   **Preferred**: Event-Driven. (e.g., `VideoPublished` event -> Newsletter module Listens).
*   **Allowed**: Service Contracts (Interfaces) injected via Container.
*   **Prohibited**: Direct usage of `Modules\Other\Models\Model` inside a different module without an interface.

---

## 9. PERFORMANCE & SCALABILITY

### 9.1 Philosophy
*   **Target**: Small-to-Medium high-performance.
*   **Caching**: Late-binding optimization. Do not cache unless profiling proves a bottleneck.
*   **Assets**: Vite-based. Strictly separation of CSS/JS.

### 9.2 Database
*   **N+1 Prevention**: Strict enforcement of Eager Loading (`with()`).
*   **Query Scope**: Use Model Scopes (`scopePublished`) to centralize filtering logic.

---

## 10. SECURITY BASELINE

1.  **Validation**: `FormRequests` for all write operations.
2.  **Output**: Auto-escaping `{{ }}`. Raw `{!! !!}` only for trusted CMS content.
3.  **Admin**: Strict middleware protection.
4.  **Links**: `rel="nofollow sponsored"` on user/external content.

---

## 11. VERSIONING & MAINTENANCE

**Update Trigger**: This document must be updated whenever:
1.  A new Module is created.
2.  A new global architectural pattern is adopted.
3.  A Feature Flag is added.

---

## 12. ANTI-PATTERNS (STRICTLY PROHIBITED)

*   ❌ **Logic in Views**: `@php ... @endphp`. (Move to View Composer).
*   ❌ **Hardcoded Paths**: `view('themes.classic.pages.home')`. (Use `view('pages.home')`).
*   ❌ **God Controllers**: Methods > 50 lines.
*   ❌ **Implicit Features**: Assuming a feature is on without checking the flag.
*   ❌ **Direct DB in Blade**: `@foreach(Post::all() as $post)`. (Pass from Controller).

---

## 13. ENCODING POLICY

All source files MUST adhere to strict encoding standards to prevent character corruption.

### 13.1 Requirements
*   **Encoding**: UTF-8 (without BOM)
*   **Line Endings**: LF (Unix-style)
*   **Final Newline**: All files must end with a single newline

### 13.2 Prohibited
*   ❌ **BOM (Byte Order Mark)**: Files must NOT contain UTF-8 BOM (`EF BB BF`)
*   ❌ **CRLF Line Endings**: Windows-style line endings are not allowed
*   ❌ **ANSI / Windows-1256**: Legacy encodings are strictly prohibited

### 13.3 Enforcement
*   `.editorconfig` is configured at project root
*   Encoding violations MUST fail code review
*   All IDE/editors MUST be configured to use UTF-8 without BOM and LF line endings

---

## 14. THEME BOUNDARY ENFORCEMENT

**Added**: 2026-02-06 | **Updated by**: Architectural Refactor

Themes are **presentation layers only**. They must remain "dumb" views that receive data and display it.

### 14.1 Core Rules

| Rule | Description |
| :--- | :--- |
| **No DB Queries** | Views must NEVER execute database queries (`::where()`, `::pluck()`, `::exists()`, `::count()`) |
| **No Module References** | Themes must NEVER directly reference `\Modules\*` classes |
| **Computed State via Composers** | Layouts must receive computed state (booleans, counts) via View Composers |
| **Controller Provides Data** | Page-specific data must be passed from Controllers |
| **Services for Counters** | Global counters (unread messages, published books) must come from Services/Composers |

### 14.2 View Composers

These composers inject computed state into layouts:

| Composer | Layout | Variables Injected |
| :--- | :--- | :--- |
| `PublicLayoutComposer` | `theme::layouts.blog` | `$hasBooks`, `$isPostPage` |
| `AdminLayoutComposer` | `theme::layouts.admin` | `$unreadMessagesCount` |
| `OwnerBioComposer` | `partials.owner-bio` | `$biography` |

**Location**: `App\View\Composers\*`
**Registration**: `App\Providers\ViewComposerServiceProvider`

### 14.3 Enforcement Pattern

**Before (Violation)**:
```blade
@if(feature('books') && \Modules\Books\Models\Book::published()->exists())
```

**After (Correct)**:
```blade
@if(feature('books') && $hasBooks)
```

### 14.4 Adding New Module UI Dependencies

1.  Create a method in the relevant Composer (or create new Composer)
2.  Check feature flag and class existence in Composer
3.  Cache the result appropriately
4.  Inject boolean/count into view
5.  Use injected variable in Blade

---

## 15. ENCODING & FILE STANDARDS

**Status**: Enforced

### 15.1 Required Standards

| Standard | Value | Enforcement |
| :--- | :--- | :--- |
| **Encoding** | UTF-8 (no BOM) | `.editorconfig`, pre-commit checks |
| **Line Endings** | LF (Unix) | `.editorconfig`, git auto-conversion |
| **Final Newline** | Required | `.editorconfig` |
| **Arabic Content** | Native UTF-8 | Encoding validation tools |

### 15.2 Prohibited

*   ❌ UTF-8 BOM (`EF BB BF` bytes)
*   ❌ CRLF line endings
*   ❌ Windows-1256, ANSI, or other legacy encodings
*   ❌ Copying Blade files between encodings without validation

### 15.3 Recovery Procedure

If encoding corruption is detected:
1.  Identify corrupted files via pattern matching (`ط§` sequences)
2.  Create backup before repair
3.  Apply character-by-character mapping reversal
4.  Validate repairs against expected Arabic text
5.  Clean up temporary scripts

---

## 16. ADMIN THEME CONTRACT

Admin themes are swappable UI implementations that must adhere to a strict contract.

### 16.1 Requirements

| Requirement | Description |
| :--- | :--- |
| **No DB Queries** | Admin themes must not execute database queries in Blade |
| **Layout Slots** | Must implement standard slots: `content`, `title`, `scripts`, `styles` |
| **No Module Hardcoding** | Must not directly reference Module classes |
| **Composer-Injected State** | Must use variables injected via `AdminLayoutComposer` |
| **Swappability** | Theme switch must not break any functionality |

### 16.2 Standard Variables

Admin layouts automatically receive:

| Variable | Type | Description |
| :--- | :--- | :--- |
| `$unreadMessagesCount` | `int` | Number of unread contact messages (0 if feature disabled) |

### 16.3 Feature Conditionals

Use feature flags to conditionally show menu items:
```blade
@if(feature('contact'))
    {{-- Show contact menu item using $unreadMessagesCount --}}
@endif
```

Do NOT check `class_exists()` in Blade. That check happens in the Composer.

---

## 17. GLOBAL STATS CONTRACT

**Added**: 2026-02-06 | **Status**: Infrastructure Ready

The Global Stats system provides an extensible way for modules to contribute global counters/flags to admin layouts without tight coupling.

### 17.1 Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     AdminLayoutComposer                      │
│  ┌─────────────────┐    ┌─────────────────────────────────┐ │
│  │   Core Stats    │ + │     GlobalStatsManager          │ │
│  │ (unreadMessages │    │  ┌───────────────────────────┐ │ │
│  │      Count)     │    │  │ Registered Providers      │ │ │
│  └─────────────────┘    │  │ ├─ ContactStatsProvider   │ │ │
│                          │  │ ├─ OrderStatsProvider     │ │ │
│                          │  │ └─ NotificationProvider   │ │ │
│                          │  └───────────────────────────┘ │ │
│                          └─────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                      Blade Layout Views
                    (receives merged stats)
```

### 17.2 Contract Interface

```php
namespace App\Contracts;

interface HasGlobalStats
{
    /**
     * Return associative array of global counters.
     * Keys should match expected Blade variable names.
     *
     * @return array<string, int|bool>
     */
    public function getGlobalStats(): array;
}
```

### 17.3 Creating a Stats Provider

**Step 1**: Create provider class in your module:

```php
namespace Modules\Orders\Providers;

use App\Contracts\HasGlobalStats;
use Modules\Orders\Models\Order;

class OrderStatsProvider implements HasGlobalStats
{
    public function getGlobalStats(): array
    {
        return [
            'pendingOrdersCount' => Order::pending()->count(),
        ];
    }
}
```

**Step 2**: Register in your module's ServiceProvider:

```php
use App\Support\GlobalStats\GlobalStatsManager;

public function boot(): void
{
    // Guard with feature flag
    if (!feature('orders')) {
        return;
    }

    $manager = app(GlobalStatsManager::class);
    $manager->register(
        \Modules\Orders\Providers\OrderStatsProvider::class,
        'orders'  // Feature flag to check
    );
}
```

### 17.4 Safety Guarantees

The GlobalStatsManager provides these guarantees:

| Guarantee | Description |
| :--- | :--- |
| **Feature Guarded** | Will not call provider if feature is disabled |
| **Class Existence** | Safely ignores non-existent classes |
| **Exception Safety** | Catches all errors, logs them, returns empty array |
| **Empty Default** | Returns `[]` if no providers registered |

### 17.5 Best Practices

1. **Always feature-guard**: Pass the feature flag when registering
2. **Cache expensive queries**: Use `Cache::remember()` in providers
3. **Use descriptive keys**: Variable names should be self-documenting
4. **Integer or Boolean only**: Stats should be simple scalar values
5. **Don't duplicate core stats**: Core stats take precedence in merge

### 17.6 Current Core Stats

These stats are always available in admin layouts (via `AdminLayoutComposer`):

| Variable | Type | Source |
| :--- | :--- | :--- |
| `$unreadMessagesCount` | `int` | Contact module |

Additional stats from GlobalStatsManager are merged additively.

---

## 18. PUBLIC LAYOUT CONTRACT

**Added**: 2026-02-07 | **Status**: Enforced | **Version**: 2.4.0

The `layouts.blog` layout depends on the following injected variables from `PublicLayoutComposer`:

### 18.1 Required Variables

| Variable | Type | Description |
| :--- | :--- | :--- |
| `$hasBooks` | `bool` | Whether published books exist (feature-guarded) |
| `$isPostPage` | `bool` | Whether current page is a single post (`post.show`) |

### 18.2 Variables from ViewComposerServiceProvider (Global)

| Variable | Type | Description |
| :--- | :--- | :--- |
| `$trendingRecentPosts` | `Collection` | Latest published posts |
| `$trendingMostLikedPosts` | `Collection` | Most liked posts |
| `$trendingMostReadPosts` | `Collection` | Most viewed posts |

### 18.3 Variables from AppServiceProvider (Sidebar)

| Variable | Type | Description |
| :--- | :--- | :--- |
| `$mostLikedPosts` | `Collection` | Top liked posts for sidebar |
| `$mostReadPosts` | `Collection` | Top viewed posts for sidebar |

### 18.4 Architectural Rules

1. **Layouts MUST NOT compute route state** — Use injected `$isPostPage` instead of `request()->routeIs()` in Blade
2. **Layouts MUST NOT depend on controller-local variables** — All layout state comes from Composers
3. **All layout state MUST be injected via View Composers** — No `@php` blocks for state computation
4. **Feature flags MUST NOT introduce hidden layout dependencies** — All conditional variables must be injected with safe defaults

### 18.5 Why This Matters

The Newsletter module uses `$isPostPage` to conditionally hide the footer newsletter form on post pages (where an inline form already exists). Without proper injection, enabling the Newsletter feature exposes the undefined variable.

---

## CHANGELOG

| Date | Version | Change |
| :--- | :--- | :--- |
| 2026-02-08 | 2.6.0 | Added Thoughts module (§3.1): Admin CRUD for short-form content |
| 2026-02-08 | 2.5.0 | Added Landing module (§3.1), Module Route Override Architecture (§3.3) |
| 2026-02-07 | 2.4.0 | Added Public Layout Contract (§18): `$isPostPage` injection |
| 2026-02-06 | 2.3.0 | Added Global Stats Contract (§17) |
| 2026-02-06 | 2.2.0 | Added Theme Boundary Enforcement (§14), File Standards (§15), Admin Theme Contract (§16) |
| 2026-02-01 | 2.1.0 | Added Encoding Policy (§13) |
| 2026-01-15 | 2.0.0 | Initial architecture document |
