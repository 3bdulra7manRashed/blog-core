# SYSTEM CONSTITUTION

> **System:** Blog Core Modular Monolith
> **Version:** 2.0.0 (Governance Edition)
> **Enforcement:** Strict
> **Authority:** Higher than any other documentation.

---

## 🏛 1. Architectural Philosophy

The system architecture is a **Modular Monolith** designed for strict isolation, predictable scaling, and theme-agnostic presentation.

### **Core Tenets**
1.  **Unidirectional Dependency:** Core never depends on Modules. Modules depend on Core contracts.
2.  **Theme Agnosticism:** Logic resides in Modules; Presentation resides in Themes.
3.  **Strict encapsulation:** Usage of internal module classes by other modules is forbidden unless via Contract/Interface.
4.  **Feature Flagging:** Every module must be togglable at runtime without code changes.

---

## 🧱 2. Layer Boundaries & Data Ownership

### **Layer Defintions**
| Layer | Namespace | Role | Allowed Dependencies |
|-------|-----------|------|-----------------------|
| **Core** | `App\` | Foundation, Auth, Base Models, Contracts | None (System only) |
| **Modules** | `Modules\{Name}\` | Business Logic, Domain Features | `App\`, `Illuminate\` |
| **Themes** | N/A | Presentation Overrides | `App\`, `Modules\` (View-only) |

### **Dependency Direction Graph**
```text
[Themes] ──> [Modules] ──> [Core]
   │             │
   └─────────────┴──> [Framework]
```
❌ **VIOLATION:** `Core` importing `Modules\Media`.
❌ **VIOLATION:** `Modules\Vod` importing `Modules\Newsletter` directly.

### **Data Ownership Model**
1.  **Module Tables:** Modules own their tables (e.g., `vod_contents`).
2.  **Core Tables:** Core owns `users`, `settings`, `jobs`.
3.  **Cross-Module Joins:** Strictly Forbidden. Use Service Interfaces or Event sourcing.
4.  **Foreign Keys:** 
    *   Allowed: Module -> Core (e.g., `vod_contents.user_id` -> `users.id`).
    *   Forbidden: Core -> Module (e.g., `users.featured_vod_id` -> `vod_contents.id`).

---

## 📜 3. Module Boot Lifecycle & Registration

The system follows a strict boot sequence to ensure stability.

### **Boot Sequence Model**
1.  **Framework Boot:** Laravel Core.
2.  **Core Providers:** `AppServiceProvider`, `AuthServiceProvider`.
3.  **Theme System:** `ThemeServiceProvider` (Sets view paths).
4.  **Module Safety:** `ModulesServiceProvider` (Registers "Ghost Components" for disabled modules).
5.  **Module Providers:** Individual Module ServiceProviders (Routes, Views).

### **Registration Contract**
*   **Source of Truth:** `bootstrap/providers.php`.
*   **Prohibited:** `config/app.php` (Legacy/Deprecated).
*   **Orphaned Providers:** Any provider not registered here is considered dead code.

---

## 🎨 4. Theme Protocol & View Resolution

The system uses a **Theme-First, Module-Fallback** resolution strategy to allow complete UI customization without touching module code.

### **Resolution Stack**
1.  **Theme Override:** `resources/themes/{active_theme}/views/admin/vod/...`
2.  **Core Fallback:** `resources/views/admin/vod/...`
3.  **Module Default:** `Modules/Vod/resources/views/admin/vod/...`

### **Theme Governance**
1.  **Namespace Ban:** Usage of `view('vod::index')` is **STRICTLY FORBIDDEN**. It locks the view to the module.
2.  **Dot Notation:** Usage of `view('admin.vod.index')` is **MANDATORY**.
3.  **Path Registration:** Modules must register views with:
    ```php
    $this->loadViewsFrom(__DIR__.'/../resources/views', '');
    ```
4.  **No Theme Logic in Modules:** Modules must NEVER detect the active theme. `ThemeServiceProvider` handles all theme path logic.

---

## 🛡 5. Feature Enforcement & Middleware

### **Enforcement Contract**
Every module must be protected by the **Tri-Layer Defense**:

1.  **Route Level (Middleware):**
    *   `Route::middleware(['web', 'auth', 'feature:vod'])`
    *   This is the primary security barrier.
2.  **Boot Level (Service Provider):**
    *   `if (!feature('vod')) return;` in `boot()`.
    *   Prevents registering routes/views for disabled modules (Performance).
3.  **UI Level (Blade):**
    *   `@module('vod') ... @endmodule`
    *   UX only. Not security.

### **Missing Guards**
Any public route without a `feature:` middleware is a **Critical Security Vulnerability**.

---

## 11. Plan System & Feature Override Architecture

The system implements a **Hybrid Plan + Feature Override** architecture. This allows features to be controlled primarily by a "Client Plan" (bundling multiple features) while maintaining the flexibility to manually override specific features via environment variables.

### **1. System Modes**
The architecture supports three distinct operating modes controlled by `.env`:

#### **A) Plan Mode (Default)**
*   `USE_PLAN_SYSTEM=true`
*   `CLIENT_PLAN=basic|pro|business`
*   Features are derived entirely from `config/plans.php` definitions for the active plan.

#### **B) Plan + Override Mode**
*   `USE_PLAN_SYSTEM=true`
*   Specific features have explicit values in `.env` (e.g., `FEATURE_VOD_VIDEO=true`).
*   The explicit env variable **overrides** the plan's default value.

#### **C) Pure Feature Mode (Legacy/Dev)**
*   `USE_PLAN_SYSTEM=false`
*   Plans are ignored.
*   Features are controlled solely by `FEATURE_XXX` environment variables.

### **2. Override Precedence Logic**
The `resolve_feature()` logic in `config/features.php` follows this strict decision order:

| Priority | Condition | Source of Truth |
|:--------:|-----------|-----------------|
| **1** | `USE_PLAN_SYSTEM=false` | `FEATURE_XXX` env variable (Fallback: feature default) |
| **2** | `USE_PLAN_SYSTEM=true` AND `FEATURE_XXX` is set | `FEATURE_XXX` env variable (Overrides Plan) |
| **3** | `USE_PLAN_SYSTEM=true` AND `FEATURE_XXX` is empty | `config/plans.php` [Plan][Feature] |
| **4** | Fallback | `false` |

### **3. Architectural Rules**
1.  **Definition Source:** Plans are strictly defined in `config/plans.php`.
2.  **Resolution Source:** Features are strictly resolved in `config/features.php`.
3.  **No Logic in Plans:** Plan arrays must contain only booleans. No conditional logic.
4.  **No Cross-Module Refs:** Plans must not reference other modules directly; they map to feature keys.
5.  **Strict Usage:**
    *   **Controllers:** MUST use `config('features.xxx')`.
    *   **Middleware:** MUST use `feature('xxx')` helper.
    *   **Forbidden:** Using `env('CLIENT_PLAN')` or `env('FEATURE_...')` outside of config files.

### **4. Backward Compatibility**
To ensure stability across the modular monolith:
*   **Config Structure:** `config('features.vod.video')` access remains unchanged.
*   **Helper:** `feature('vod')` helper behaves exactly as before.
*   **Middleware:** `Route::middleware('feature:vod')` continues to function without modification.
*   **Service Providers:** Module `boot()` methods require no changes.

### **5. Production Safety**
*   **Caching:** `php artisan config:cache` MUST succeed. The resolver logic is compatible with configuration caching.
*   **Plan Switching:** Changing `CLIENT_PLAN` in production requires `php artisan config:clear` to take effect if cached.
*   **Immutable Env:** `.env` should not be writable by the web server (except via restricted admin tools).
*   **Scope:** The plan system is currently **Installation-Level**. It applies to the entire running instance, not per-user or multi-tenant.

### **6. Future SaaS Migration Path**
*   **Database Migration:** The current array-based plan logic in `config/plans.php` can be swapped for a database-driven provider without changing correct application code.
*   **Tenant Context:** The `resolve_feature` closure can be updated to accept a generic `Tenant` context in the future for multi-tenancy.
*   **No Refactor needed:** Existing calls to `feature()` will seamlessly inherit the new logic.

---

## ⚙ 6. Configuration & Naming Standards

### **Naming Conventions**
| Concept | Convention | Example |
|---------|------------|---------|
| Module Name | PascalCase | `Modules/Newsletter` |
| View Path | Kebab-case | `resources/views/admin/vod` |
| Route Name | distinct.dot | `admin.vod.playlists.index` |
| Config Key | snake_case | `config('features.vod')` |
| Table Name | snake_plural | `vod_contents` |

### **Config Scattering**
*   **Forbidden:** Defining module config in `config/app.php`.
*   **Mandatory:** `Modules/{Name}/Config/config.php` merged in boot.

---

## 🚫 7. Anti-Patterns (Refactoring Targets)

The following patterns exist in the legacy codebase and are flagged for immediate refactoring:

1.  **Provider Split:** Loading modules via `config/app.php` instead of `bootstrap/providers.php`.
2.  **Core Pollution:** `User` model having `media()` relationship (Core depending on Module).
3.  **Ghost Module:** `ModulesServiceProvider` not being registered, breaking disabled module safety.
4.  **Directory Casing:** Lowercase `Modules/newsletter` directory (Linux crash risk).
5.  **View Locking:** Legacy modules using `::` namespace.

---

## 📝 8. Governance & PR Rules

1.  **No New Core Dependencies:** PRs introducing `use Modules\...` in `app/` will be rejected.
2.  **No New Config in App:** PRs adding keys to `config/app.php` will be rejected.
3.  **Strict Middleware:** New routes must have Feature Middleware.
4.  **Theme Check:** New views must verify Theme Overrides work.
