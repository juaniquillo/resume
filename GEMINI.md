# Project Context & Guidelines 

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
A cache strategy is implemented at the `ResumePresenter` level, utilizing a modular `ResumeThemeCacheManager` to optimize performance for public views.

### 6. Recent Architectural Improvements
- **OG Image Versioning**: Implemented an `og_image_version` in `GeneralOption` to act as a manual/automatic cache buster for social sharing.
- **Ultra-Wide Screen Support**: Standardized on the `2xl:` breakpoint across all themes and the landing page to provide a superior experience on displays larger than 1536px.
- **Cascading Deletions & File Cleanup**: The `User` model includes a `deleting` hook that ensures all associated resume data and physical files (images, exports, imports) are purged from storage, preventing orphan data.

## 🛠️ Development Standards

### 1. Code Quality
- **Type Safety**: Always use strict typing, return types, and PHP 8.4 features (e.g., property promotion, constructor hooks).
- **PHPStan**: Maintain Level 5 compliance. Always use FQCNs in `@var` tags for cross-namespace clarity.
- **Pint**: Run `vendor/bin/pint --format agent` before finalizing any change.

### 2. Testing
- **Pest**: Every feature or refactor must be covered by a Pest test.
- **Environment Safety**: ALWAYS run tests with `$env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:';`.
- **Browser Testing**: For UI changes, smoke test pages for JS errors using the browser tools.
- **Assets**: Run `npm run build` after making any visual or CSS changes to ensure assets are compiled.

### 3. Database
- **Migrations**: Always create migrations for schema changes.
- **Factories/Seeders**: Maintain high-quality factories for all models to support robust testing.

## 📋 Current Objectives & Roadmap
1. **Public JSON API**: Implement a rate-limited API that serves the resume in JSON format (JSON Resume compatible).
2. **Dashboard Performance**: Create a cache manager for high-frequency dashboard components (navigation, summary cards).
3. **Multi-User Scaling**: Optimize query consolidation for multi-tenant environments.

## 🎨 Design System
Refer to `DESIGN.md` for the "Retro-Modern" aesthetic (Space Mono, Pixel Icons, specific hex palette).
- **Breakpoint Policy**: `2xl:` is the primary target for large-screen layout enhancements (containers shift from `max-w-4xl` to `max-w-6xl`).
- **Font Policy**: All fonts MUST be served locally from `public/fonts/` as `.ttf` files to optimize LCP and ensure privacy.

---

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- laravel/fortify (FORTIFY) - v1
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- livewire/flux (FLUXUI_FREE) - v2
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Follow existing application Enum naming conventions.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== juaniquillo/laravel-backend-component rules ===

## Laravel Backend Component

This package lets you build dynamic, class-based HTML components in PHP. Instead of writing Blade HTML directly, you compose component trees via PHP objects and render them with:

```php
{{ $component }}
```

Components implement Laravel's `Htmlable`.

### Creating components

Use the `ComponentBuilder` with a `ComponentEnum`:

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON);
$div    = ComponentBuilder::make(ComponentEnum::DIV);
```

### Content management

Use **`setContent()`** for a single item and **`setContents()`** for multiple items at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setContent('Hello');                // single (no key)
    ->setContent('World', 'key_1');       // single with key
    ->setContents([...]);                 // batch (ignores keys)
    ->setContents([...], overwrite: true); // batch with keys (overwrites existing)
$div->prependContent('First');            // Prepend
$div->prependContent('Really', 'k0');     // Prepend with key
$div->unsetContent();                     // Clear all
$div->unsetContent('key_1');              // Remove by key
```

### Attributes

Use **`setAttribute()`** for a single attribute and **`setAttributes()`** for multiple at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setAttribute('id', 'my-id');        // single
    ->setAttribute('class', 'custom-class');
    ->setAttributes(['data-foo' => 'bar']); // batch
```

### Themes (Tailwind CSS)

Theme files are PHP arrays in `resources/views/_themes/tailwind/`, keyed by variant name:

```php
// resources/views/_themes/tailwind/action.blade.php
return [
    'default' => "whitespace-nowrap bg-blue-700 hover:bg-blue-800",
    'success' => "whitespace-nowrap bg-green-700 hover:bg-green-800",
    'error'   => "whitespace-nowrap bg-red-700 hover:bg-red-800",
    'link'    => "text-blue-500 underline hover:no-underline",
];
```

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')                       // single variant
    ->setTheme('table', ['th', 'th-dark'])                // array of variant keys
    ->setThemes(['action' => 'success', 'size' => 'lg']); // batch
```

### Individual components

`DivComponent` is both a utility **and** a blueprint for creating new targeted component classes that bypass the enum/builder entirely. To create a new individual component, duplicate the `DivComponent` pattern:

1. Put the class in `src/Components/Individual/`
2. Implement `BackendComponent`, `IndividualComponent`, `ThemeComponent`, `Htmlable` (omit `ContentsComponent`+`HasContent` for self-closing elements)
3. Use traits `IsBackendComponent`, `IsThemeable` (add `HasContent` only if the component can hold children)
4. Define `getName()` to return the `ComponentEnum` value (or any dotted view path)
5. Define `getComponentPath()` and `getPathOnly()` following the existing convention
6. Wire up `getAttributeBag()`, `toHtml()`, `toArray()`, and a static `make()` factory
7. Mark the class `final` (optional but recommended)

```php
use Juaniquillo\BackendComponents\Components\Individual\DivComponent;

$div = new DivComponent;
$div->setAttribute('class', 'my-class');
$div->setContent('Hello');
```

Currently only `DivComponent` exists in this category — add more as needed.

### Table utilities

TableUtil builds a complete `<table>` from head/body arrays. CellBag passes per-cell data:

```php
use Juaniquillo\BackendComponents\Utils\TableUtil;
use Juaniquillo\BackendComponents\Utils\CellBag;

$table = TableUtil::make(
    head: ['Name', 'Email', 'Role'],
    body: [
        ['Alice', 'alice@example.com', 'Admin'],
        [
            new CellBag(content: 'Bob', theme: ['color' => 'success']),
            'bob@example.com',
            'Editor',
        ],
    ],
)->getComponent();
```

### Settings

```php
$component = ComponentBuilder::make(ComponentEnum::MODAL)
    ->setSetting('transition', 'fade')
    ->setSettings(['setting_1' => 'value_1']);
```

### Livewire

```php
ComponentBuilder::make('my-livewire-component')
    ->setLivewire()
    ->setLivewireKey('my-key')
    ->setLivewireParams(['param' => 'value']);
```

### Available components

- **Template:** `TEMPLATE`
- **Collection:** `COLLECTION`
- **Block:** `DIV`, `PARAGRAPH`
- **Inline:** `BUTTON`, `LINK`, `IMG`, `SPAN`, `BOLD`, `EM`, `ITALIC`, `STRONG`, `SMALL`
- **Headers:** `H1`, `H2`, `H3`, `H4`, `H5`, `H6`
- **Form:** `FORM`, `LABEL`, `LEGEND`, `FIELDSET`, `TEXT_INPUT`, `FILE_INPUT`, `EMAIL_INPUT`, `SEARCH_INPUT`, `PASSWORD_INPUT`, `CHECKBOX_INPUT`, `HIDDEN_INPUT`, `RADIO_INPUT`, `DATALIST`, `TEXTAREA`, `SELECT`, `OPTGROUP`, `OPTION`
- **Table:** `TABLE`, `THEAD`, `TBODY`, `TFOOT`, `TR`, `TH`, `TD`, `CAPTION`, `COLGROUP`, `COL`
- **Lists:** `OL`, `UL`, `LI`
- **Details:** `DETAILS`, `SUMMARY`
- **Layers:** `DIALOG`
- **Custom:** `MODAL`

### View conventions

Each component Blade template follows:

```blade
@props(['attrs' => null])
@php
    $serverAttrs = [];
    $content = null;
    $slot = $slot ?? null;
    if ($attrs) {
        $serverAttrs = $attrs->getAttributes();
        $content = $attrs->content;
    }
@endphp
<element {{ $attributes->merge($serverAttrs) }}>{{ $content }}{{ $slot }}</element>
```

Self-closing tags (input, img, col) use `/>` instead.

### Local resolution

For apps consuming the package, three builders control which `resources/views/` directory resolves components and themes:

- **`ComponentBuilder`** — package views for both components and themes
- **`LocalComponentBuilder`** — app views for both components and themes
- **`LocalThemeComponentBuilder`** — package views for components, app views for themes

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;

$package     = ComponentBuilder::make(ComponentEnum::BUTTON);                  // package both
$local       = LocalComponentBuilder::make(ComponentEnum::BUTTON);              // app both
$localTheme  = LocalThemeComponentBuilder::make(ComponentEnum::BUTTON);         // package comp + app theme

// ComponentBuilder also supports ->useLocal() as a shorthand for LocalComponentBuilder
$component   = ComponentBuilder::make(ComponentEnum::BUTTON)->useLocal();
```

### Serialization

```php
$array = $component->toArray();
$restored = ComponentFactory::fromArray($array);
```

</laravel-boost-guidelines>
