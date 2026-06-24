# Agent Instructions

## Tech Stack
- PHP 8.4, Laravel 13
- Livewire 4, Flux UI (Free), Tailwind 4
- Pest 4 (Tests)

## Essential Commands
- **Setup**: `composer run setup` (installs, migrates, builds assets).
- **Quality Assurance**: `composer qa` (Run this BEFORE pushing/finishing tasks. It runs Lint, PHPStan, and Pest).
- **Individual Tasks**: 
    - Lint: `composer lint`
    - Analysis: `composer phpstan`
    - Testing: `php artisan test --compact`

## Architecture & Conventions
- **CrudAssistant**: All resume sections (Works, Education, Skills) are managed via `CrudAssistant`. Use its Schema patterns (Input Factories, Value Managers, Table Presenters) for new sections.
- **Backend Components**: Dynamic HTML is built in PHP using `juaniquillo/laravel-backend-component`. Use `ComponentBuilder` for UI composition.
- **Conventions**:
    - Descriptive naming (`isRegisteredForDiscounts`, not `discount()`).
    - Standardize on `2xl:` breakpoint for large screens (`max-w-6xl` containers).
    - Apply `InvalidatesResumeCache` trait to Models affecting public resumes.
    - Use FQCNs in PHPDoc tags.
- **Development**: Project is optimized for Laravel Herd.

## Rules
- **Testing**: Every change must be tested. Prefer feature tests. Use factories.
- **Formatting**: Run `vendor/bin/pint --format agent` if you change PHP files.
- **No Documentation**: Do not create documentation files unless explicitly requested.
