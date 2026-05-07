---
name: laravel-backend-component
description: Create and manage dynamic UI components in Laravel using the juaniquillo/laravel-backend-component package (https://github.com/juaniquillo/laravel-backend-component). Use when you need to build simple html elements like divs, buttons, etc, or more complex elements like forms, and tables fluently in PHP.
---

# Laravel Backend Component AI Skill

This skill assists in building dynamic, class-based UI components in Laravel. It leverages a fluent PHP API instead of traditional Blade templates, making it easier to manage component logic from controllers or view composers.

## Core Workflows

- **Component Creation**: Use `ComponentBuilder` or instantiate `MainBackendComponent`.
- **Attribute Management**: Fluent methods for class, style, and data attributes.
- **Dynamic Themes**: Apply and customize Tailwind-based themes.
- **Complex Structures**: Build nested components like Forms, Tables, and Lists.

## Quick Start

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setAttribute('class', 'bg-blue-500 text-white p-2 rounded')
    ->setContent('Click Me');
```

## Detailed Documentation

- **API Reference**: Detailed breakdown of core classes and methods in [references/api.md](references/api.md).
- **Component Enums**: List of available built-in components in [references/components.md](references/components.md).
- **Usage Examples**: Common patterns for forms, tables, and more in [references/examples.md](references/examples.md).
