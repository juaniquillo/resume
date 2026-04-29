# Usage Examples

Examples for the `laravel-backend-component` package (https://github.com/juaniquillo/laravel-backend-component).

## 1. Creating a Button
```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setAttribute('class', 'btn btn-primary')
    ->setContent('Save Changes');

echo $button->toHtml();
```

## 2. Building a Simple Form
```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$form = ComponentBuilder::make(ComponentEnum::FORM)
    ->setAttribute('method', 'POST')
    ->setAttribute('action', '/submit')
    ->setContent(
        ComponentBuilder::make(ComponentEnum::TEXT_INPUT)
            ->setAttribute('name', 'username')
            ->setAttribute('placeholder', 'Enter username')
    )
    ->setContent(
        ComponentBuilder::make(ComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setContent('Submit')
    );
```

## 5. Bulk Attribute and Content Assignment

### Using `setAttributes()`
Instead of chaining multiple `setAttribute` calls, you can pass an array.

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$input = ComponentBuilder::make(ComponentEnum::TEXT_INPUT)
    ->setAttributes([
        'name' => 'email',
        'type' => 'email',
        'placeholder' => 'your@email.com',
        'required' => 'required',
        'class' => 'form-control border-blue-500'
    ]);
```

### Using `setContents()`
Useful for adding multiple child components or text blocks at once.

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$list = ComponentBuilder::make(ComponentEnum::UL)
    ->setContents([
        ComponentBuilder::make(ComponentEnum::LI)->setContent('First Item'),
        ComponentBuilder::make(ComponentEnum::LI)->setContent('Second Item'),
        ComponentBuilder::make(ComponentEnum::LI)->setContent('Third Item'),
    ]);
```

### Package Themes (Default)
The package includes built-in themes for core components.

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme("background", "default")
    ->setContent("Standard Theme");
```

### Local Themes (Custom)
To use your own local themes, create a Blade file at `resources/views/_themes/tailwind/background.blade.php`:

```php
<?php
// Default path (resources/views/_themes/tailwind/background.blade.php)
return [
    "default" => "bg-blue-700",
    "error" => "bg-red-700",
    "success" => "bg-teal-100",
];
```

Then, use the `LocalThemeManager` or the `LocalThemeComponentBuilder`:

```php
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

// This will look for theme keys in your local resources folder
$button = LocalThemeComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme("background", "success")
    ->setContent("Local Success Theme");

// Alternatively, using the Main class directly:
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;

$button = new MainBackendComponent(ComponentEnum::BUTTON, new LocalThemeManager)
    ->setTheme("background", "error");
```
