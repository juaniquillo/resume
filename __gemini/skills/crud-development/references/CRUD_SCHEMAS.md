# CRUD Schemas

The CRUD Schema class orchestrates the inputs and provides helpers for generating forms and tables.

## Implementation

```php
final class MyEntityCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm, HasHtmlTable, IsCrud;

    public function inputsArray(): array
    {
        return [
            'uuid' => UuidFactory::make(),
            'name' => NameFactory::make(),
            // ...
        ];
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        // Custom layout logic
        return $this->formFullSpanInputs(['summary']);
    }

    protected function tableOptions(TableRowsAction $action): void
    {
        // Define buttons for the dashboard table
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {
                $helper = TableHelpers::make();
                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents([
                        $helper->editButton(route('dashboard.entity.edit', [$model->id])),
                        $helper->deleteButton(route('dashboard.entity.destroy', [$model->id])),
                    ]);
            }
        );
        $action->setExtraCell('Settings', $recipe);
    }
}
```

## Traits

- `HasHtmlForm`: Adds `form()`, `setFormAction()`, `setFormMethod()`.
- `HasHtmlTable`: Adds `makeTable()`.
- `IsCrud`: Adds `make()`, `getModel()`, `getValues()`, `getErrors()`.
