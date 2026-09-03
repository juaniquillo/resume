# Architecture & Core Patterns

Resume Manager is built around robust, reusable architectural patterns designed to streamline CRUD operations and dynamic component rendering.

## 1. CrudAssistant
All resume sections (Works, Education, Skills, etc.) are managed via **CrudAssistant** (`juaniquillo/crud-assistant`). This package standardizes module scaffolding using:
- **Input Factories**: Streamline form inputs and validation rules across modules.
- **Value Managers**: Handle database persistence and data transformation.
- **Table Presenters**: Power clean, tabular management views in the dashboard.

## 2. Laravel Backend Component (`juaniquillo/laravel-backend-component`)
Instead of writing static Blade HTML directly for complex dynamic views, component trees are composed in PHP objects:
- Use `ComponentBuilder` and `ComponentEnum` to compose buttons, divs, tables, and modals.
- Themes accumulate cleanly and integrate directly with Tailwind CSS variants.
- Models affecting public resumes apply the `InvalidatesResumeCache` trait automatically.

## 3. Resume Caching & Invalidation
Resume Manager implements robust caching to ensure lightning-fast public profile rendering and minimal database queries:
- **Automatic Caching**: Public resume views are cached per user and presenter theme.
- **Automatic Invalidation**: Whenever you update any resume section or model (Basics, Work, Education, Skills, Highlights, etc.), the `InvalidatesResumeCache` trait automatically clears the resume cache so your visitors always see the latest updates.
- **Manual Cache Clearing**: 
  - **For Users**: You can instantly clear your resume cache at any time using the **"Clear Resume Cache"** section inside your dashboard.
  - **For Developers**: You can also flush application caches via Artisan (`php artisan cache:clear`).
