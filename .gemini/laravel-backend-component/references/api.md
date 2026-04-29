# API Reference

The `laravel-backend-component` package (https://github.com/juaniquillo/laravel-backend-component) provides several key classes for building components.

## Architecture

The package follows a core-driven architecture:
- **`src/MainBackendComponent.php`**: The entry point for components.
- **`src/Builders/`**: Static builders to simplify instantiation.
- **`src/Concerns/`**: Trait-based logic for content, attributes, slots, and themes.
- **`src/Contracts/`**: Interfaces to ensure consistent behavior.
- **`src/Enums/`**: Enums for type-safe component paths.

## Main Classes

### `MainBackendComponent`
The primary class representing a component. It handles attributes, contents, themes, and rendering.

**Methods:**
- `__construct(string|BackedEnum $name, ThemeManager $themeManager = new DefaultThemeManager)`
- `setAttribute(string $name, int|string|null $content)`: Sets a single attribute.
- `setAttributes(array $attributes)`: Sets multiple attributes at once.
- `setContent(int|string|CompoundComponent|BackendComponent $content, string|int|null $key = null)`: Sets a single content item.
- `setContents(array $contents)`: Sets multiple content items at once.
- `setSlot(int|string $name, CompoundComponent|BackendComponent $slot)`: Sets a named slot.
- `setTheme(string $name, string|array $theme)`: Applies a theme.
- `toHtml()`: Renders the component to a string.

### Theme Managers
The package uses theme managers to resolve Tailwind classes from theme files.

- `DefaultThemeManager`: Uses the package's internal themes.
- `LocalThemeManager`: Uses your application's local theme files (default path: `resources/views/_themes/tailwind/`).

**Methods:**
- `setTheme(string $name, string|array $theme)`: Applies a specific theme from a theme file.
- `setThemes(array $themes)`: Applies multiple themes at once.
- `setThemeManager(ThemeManager $themeManager)`: Switches the theme manager instance.

### Theme Builders
- `LocalThemeComponentBuilder`: A builder pre-configured to use the `LocalThemeManager`.

**Methods:**
- `static make(string|BackedEnum $name)`: Creates a component using local themes.

## Key Traits (Concerns)

The `MainBackendComponent` uses several traits that provide additional functionality:
- `HasContent`: Manages the primary content/body of the component.
- `HasPath`: Manages the view path (e.g., `inline.button`).
- `HasSettings`: Manages boolean settings or flags.
- `HasSlots`: Manages named Blade-like slots.
- `IsBackendComponent`: Core logic for attribute management and rendering.
- `IsThemeable`: Logic for applying and compiling themes.

## Helpers

- `makeBackendComponent(string|BackedEnum $name, ThemeManager $manager = new DefaultThemeManager)`: Helper function to create a component.
- `backendComponentNamespace()`: Returns the package view namespace.
