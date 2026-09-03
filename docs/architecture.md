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
