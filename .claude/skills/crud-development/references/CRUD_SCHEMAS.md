# CRUD Schemas

The CRUD Schema class orchestrates input factories and defines the visual structure of forms and tables using the `BackendComponent` system.

## Implementation

```php
<?php

namespace App\Cruds\Squema\MyEntity;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Squema\MyEntity\Inputs\NameFactory;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class MyEntityCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm, HasHtmlTable, IsCrud;

    public function inputsArray(): array
    {
        return [
            NameFactory::NAME => NameFactory::make(),
        ];
    }

    public function form(?array $inputs = null): BackendComponent|CompoundComponent
    {
        // Use formFullSpanInputs to make specific fields take the full width
        return $this->formFullSpanInputs(['description']);
    }
}
```

## Advanced Formatting

Use `fieldsetWrap()` to group related inputs with a title and border:

```php
$this->fieldsetWrap([
    'field_1' => Factory1::make(),
    $this->separator('sep1'),
    'field_2' => Factory2::make(),
], 'group_id', 'Group Title');
```

## Traits

- `HasHtmlForm`: Orchestrates `BackendComponent` form generation.
- `HasHtmlTable`: Orchestrates `BackendComponent` table generation.
- `IsCrud`: Provides context (model, values, errors) to the schema.
