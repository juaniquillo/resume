# Input Factories

Input factories are the atomic unit of our CRUD system. They define everything about a field across all layers of the application.

## Pattern

```php
class NameFactory
{
    const NAME = 'name';
    const LABEL = 'Name';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::import($input);
        self::export($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(new LaravelValidationRulesRecipe(['required', 'string', 'max:255']));
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'badge' => 'required',
                            'icon' => 'user',
                        ])
                )
        );
    }
    
    // ... import, export, factory recipes
}
```

## Available Recipes

- `LaravelValidationRulesRecipe`: Standard Laravel rules.
- `InputComponentRecipe`: UI attributes for Flux components.
- `ModelToExportRecipe`: Mapping for JSON resume export.
- `LaravelFactoryRecipe`: Logic for Database Factories.
- `TableRowsRecipe`: Rendering logic for the dashboard table.
