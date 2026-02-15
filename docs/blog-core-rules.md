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
