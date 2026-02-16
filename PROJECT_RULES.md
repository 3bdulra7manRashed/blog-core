# Project Rules & Architectural Guidelines

This document serves as the **Single Source of Truth** for the project architecture, coding standards, and deployment rules. All developers and AI agents must adhere to these guidelines to ensure system stability, production safety, and maintainability.

---

## 1. Modular Architecture

The application follows a **Modular Monolith** structure.

### 1.1 Directory Structure
- **Core Application:** `app/` (Models, Controllers, Services shared across modules)
- **Modules:** `modules/{ModuleName}/`
  - Each module is self-contained.
  - Namespace: `Modules\{ModuleName}\`
  - Service Provider: `Modules\{ModuleName}\Providers\{ModuleName}ServiceProvider`

### 1.2 Module Conventions
- **Database Directory:** MUST be lowercase: `modules/{ModuleName}/database/migrations`.
  - **Reason:** Linux case-sensitivity compatibility.
  - **Rule:** Never use `Database` (capitalized).
- **Service Providers:** Load migrations using:
  ```php
  $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
  ```

---

## 2. Feature Flag & Plan System

The feature system is designed to be **cache-safe** and **container-ready**.

### 2.1 Configuration Files
1.  **`config/plans.php`**
    - **Purpose:** Defines default feature sets for plans (basic, pro, business).
    - **Rule:** MUST contain **static arrays only**. NO `env()` calls, NO closures, NO dynamic logic.
    - **Reason:** It is loaded via `require` inside `config/features.php`.

2.  **`config/features.php`**
    - **Purpose:** Resolves final feature status.
    - **Logic:**
        1. Load `config/plans.php`.
        2. Check `USE_PLAN_SYSTEM` env var.
        3. Determine overrides from `FEATURE_XXX` env vars.
    - **Rule:** This is the **ONLY** place where `env('FEATURE_...')` is allowed.
    - **Output:** A plain array of booleans.
    - **Reason:** Ensures `php artisan config:cache` works correctly.

### 2.2 Usage in Code
- **Allowed:** `feature('newsletter')` or `config('features.newsletter')`.
- **FORBIDDEN:** Direct `env('FEATURE_NEWSLETTER')` calls in App code.

---

## 3. Database & Migrations

### 3.1 Migration Safety (Production Rule)
Migrations referencing **optional module tables** (e.g., `downloads`, `vod_contents`) MUST be defensive.

**Pattern:**
```php
public function up(): void
{
    if (!Schema::hasTable('downloads')) {
        return; // Silently skip if module is missing
    }

    Schema::table('downloads', function (Blueprint $table) {
        if (!Schema::hasColumn('downloads', 'is_active')) {
            $table->boolean('is_active')->default(true);
        }
    });
}
```

### 3.2 Factories
- **Rule:** Use the global `fake()` helper.
- **FORBIDDEN:** `$this->faker` property (deprecated in Laravel 10+, returns null in Docker production).
- **Namespace:** Factories for modules must properly reference the module model (e.g., `Modules\Media\Models\Media`).

### 3.3 Seeders
- **Rule:** Never use `env()` checks. Use `config()` values.
- **Reason:** `env()` returns null when config is cached.

---

## 4. Branding & Configuration

### 4.1 Branding System
- **Config:** `config/branding.php`
- **Control:** All branding is controlled via `.env` variables starting with `BRANDING_*`.
- **Scope:** Site Identity, SEO defaults, Author Metadata, Social Links.

### 4.2 Environment Variables
- **Rule:** Add new variables to `.env.example` with clear comments.
- **Forbidden:** Never commit `.env` to version control.

---

## 5. Deployment & PSR-4

### 5.1 Docker Compatibility
- The application runs in Docker containers on Linux.
- **Case Sensitivity:** All file references must match actual filename casing (Windows developers BEWARE).
- **Autoload:** CI/CD runs `composer dump-autoload -o`.

### 5.2 PSR-4 Compliance
- `modules/Contact/Mail/ContactMessage.php` → Namespace: `Modules\Contact\Mail`
- **Rule:** Namespace must strictly match the directory path relative to the PSR-4 root.

---

## 6. Frontend / UI
- **Stack:** Blade + Tailwind CSS (bundled via Vite).
- **Admin Theme:** "Writer" theme (resources/themes/writer).
- **Public Theme:** "Classic" theme (resources/themes/classic).
- **Rule:** Use Tailwind classes. Avoid custom CSS unless necessary.

---

## 7. Workflow Checklist

Before committing code, verify:
1.  [ ] **Config Cache:** Run `php artisan config:cache` to ensure no closures/env calls break it.
2.  [ ] **Autoload:** Run `composer dump-autoload -o` to catch PSR-4/casing issues.
3.  [ ] **Migrations:** Run `php artisan migrate --force` to ensure safeguards work.
4.  [ ] **Seeding:** Run `php artisan db:seed --force` (Factory syntax check).
