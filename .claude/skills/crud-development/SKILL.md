---
name: crud-development
description: Guide for creating and extending CRUD modules in this project. Use when the user asks to "create a new section", "add a CRUD", "implement a form for X", or "add fields to Y". Covers migrations, models, input factories, CRUD schemas, actions, controllers, and views.
---

# CRUD Development Skill

This skill provides a standardized workflow for building CRUD (Create, Read, Update, Delete) modules using the project's specialized architecture.

## Overview

The project uses a highly modular "Schema-driven" CRUD system. Instead of defining forms and validation rules in multiple places, we define them once in a **CRUD Schema** using **Input Factories**.

## Directory Structure

A full CRUD module typically involves:

```text
app/
├── Actions/Resume/{Entity}/      # Business logic (Create/Update actions)
├── Cruds/Squema/{Entity}/        # CRUD Schema and Input Factories
├── Http/Controllers/             # Thin controllers
├── Http/Requests/                # Form requests (using CRUD for rules)
├── Models/                       # Eloquent models
database/
└── migrations/                   # Database tables
resources/views/dashboard/{entity}/ # Blade views
```

## Workflow

### 1. Database & Model
- Create the migration: `php artisan make:migration create_{table}_table`.
- Create the model: `php artisan make:model {Entity}`.
- Use traits: `Uuidable`, `InvalidatesResumeCache` (mandatory if it affects the public resume), `HasHighlights`, or `HasCourses`.
- Ensure all models have proper DocBlocks with `@property-read` for all columns.

### 2. Input Factories
Each field must have an Input Factory in `app/Cruds/Squema/{Entity}/Inputs/`.
Factories define:
- **Validation**: Rules (using `LaravelValidationRulesRecipe`), labels, and custom messages.
- **Form**: UI attributes (label, icon, placeholder, badge) using `InputComponentRecipe`.
- **Consistency**: Reuse existing factories (like `UrlFactory`, `DateFactory`) when possible to maintain uniform validation (e.g., `after_or_equal:1900-01-01`).

### 3. CRUD Schema
Create a class in `app/Cruds/Squema/{Entity}/{Entity}Crud.php`.
- Implement `CrudForm`, `CrudTable`, and `CrudInterface`.
- Use `HasHtmlForm`, `HasHtmlTable`, and `IsCrud` traits.
- Define the `inputsArray()` method returning the factory instances.
- For complex layouts, use `fieldsetWrap()` or `formFullSpanInputs()`.

### 4. Actions
Implement persistence in `app/Actions/Resume/{Entity}/`:
- `Create{Entity}`: Handles creation.
- `Update{Entity}`: Handles updates.
- Ensure actions are resolved via the container or instantiated with necessary dependencies.

### 5. Controller & Request
- Create a `FormRequest` that resolves rules via the CRUD.
- Create a Controller (or single-action controllers) to orchestrate.

### 6. UI & Navigation
- Create the view using the dashboard layout and the `$form` variable.
- Add the item to `app/Components/Nav/DashboardNav.php`.

## Reference Materials

For detailed patterns and code examples, see:
- [MIGRATIONS_AND_MODELS.md](references/MIGRATIONS_AND_MODELS.md)
- [INPUT_FACTORIES.md](references/INPUT_FACTORIES.md)
- [CRUD_SCHEMAS.md](references/CRUD_SCHEMAS.md)
- [ACTIONS_AND_CONTROLLERS.md](references/ACTIONS_AND_CONTROLLERS.md)
- [TESTING.md](references/TESTING.md)



