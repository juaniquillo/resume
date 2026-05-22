# Project Context & Guidelines (Antigravity CLI)

Welcome to the **Resume Juaniquillo** project. This file serves as the primary technical context and operational manual for any AI agent interacting with this codebase.

## 🚀 Technology Stack
- **Backend:** PHP 8.4 + Laravel 13.
- **Frontend:** Livewire 4 + Alpine.js + Tailwind CSS 4.
- **UI Framework:** Flux UI (v2) + [juaniquillo/laravel-backend-component](https://github.com/juaniquillo/laravel-backend-component).
- **Authentication:** Laravel Fortify.
- **Testing:** Pest PHP 4.
- **Quality:** PHPStan (Level 5) + Laravel Pint.

## 🏗️ Architectural Core

### 1. Modular Presenters (The "Resume" System)
The resume presentation is handled by `App\Presenters\ResumePresenter`, which acts as an orchestrator. It delegates rendering to 13 specialized presenters located in `app/Presenters/Resume/` (e.g., `WorkPresenter`, `BasicsPresenter`, `SkillsPresenter`).
- **Pattern:** Each sub-presenter extends a base or uses traits like `CanComposeResumeComponents` to build a tree of `BackendComponent` objects.
- **Theming:** Styling is strictly separated from logic. Presenters use the `PresenterTheme` contract to resolve themes. The default implementation is `TailwindPresenterTheme` which maps semantic variant names to Tailwind classes defined in `resources/views/_themes/tailwind/default.blade.php`.

### 2. CRUD Management (The "Squema" Pattern)
CRUDs are built using a specialized "Squema" architecture located in `app/Cruds/Squema/`.
- **Definition:** Each entity has a dedicated CRUD class (e.g., `AwardsCrud`) that implements `CrudInterface`, `CrudForm`, and `CrudTable`.
- **Components:** Forms and tables are composed using the `BackendComponent` system.
- **Input Factories:** Field definitions are abstracted into factory classes (e.g., `TitleFactory`) within the entity's `Inputs/` subdirectory, ensuring consistency across forms and validation.
- **Logic Separation:** Controllers handle routing and authorization, while the CRUD Squema classes define the visual structure and behavior of the administrative interface.

### 3. Backend Components
The project heavily utilizes the **Backend Component** pattern. UIs are composed in PHP using a fluent API.
- **Root Resolution:** Use `LocalThemeComponentBuilder` to resolve components with theme support.
- **Granularity:** Themes are granular. For example, `BasicsPresenter` uses `emailThemes()`, `phoneThemes()`, and `profileThemes()` to style individual contact items.

### 4. Persistence & Logic (Actions)
All database operations and complex business logic MUST be encapsulated in **Laravel Actions** under `app/Actions/`.
- **Existing:** `app/Actions/Fortify/` and `app/Actions/Resume/`.
- **Constraint:** Do not put logic in Controllers or Models.

### 5. Cache Strategy
A cache strategy is planned for the `ResumePresenter` orchestrator level to optimize performance for public resume views.

## 🛠️ Development Standards

### 1. Code Quality
- **Type Safety:** Always use strict typing, return types, and PHP 8.4 features (e.g., property promotion).
- **PHPStan:** Maintain Level 5 compliance for all new code.
- **Pint:** Run `vendor/bin/pint --format agent` before finalizing any change.

### 2. Testing
- **Pest:** Every feature or refactor must be covered by a Pest test.
- **Browser Testing:** For UI changes, smoke test pages for JS errors using the browser tools.

### 3. Database
- **Migrations:** Always create migrations for schema changes.
- **Factories/Seeders:** Maintain high-quality factories for all models to support robust testing.

## 📋 Current Objectives & Roadmap
1. **Slug Management:** Implement a CRUD/Action to allow users to update their public `slug` (must be unique and validated).
2. **Cache Implementation:** Add granular caching to the modular presenter system.
3. **Resume Options:** Create a CRUD for toggling resume sections and adjusting their order.

## 🎨 Design System
Refer to `DESIGN.md` for the "Retro-Modern" aesthetic (Space Mono, Pixel Icons, specific hex palette).

---
*Legacy Note: This context was synthesized by Gemini CLI before its transition to Antigravity CLI.*
