# Blog Core System Architecture & Rules

> **Version**: 2.1.0
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

### 3.2 Theme Architecture
The system supports distinct themes for Public and Admin interfaces.
*   **Public Theme**: Controls the visitor experience.
*   **Admin Theme**: Controls the backend UI. Independently swappable.
*   **Resolution Rule**: NEVER use hardcoded paths like `resources/views/themes/x`. Use the Theme View Finder or generic namespaced views.
*   **Isolation**: Admin themes must implement the full Admin Contract (layout slots, components) to ensure compatibility.

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
