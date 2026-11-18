---
name: table-component-action
description: Build table cells and complete HTML tables with the juaniquillo/table-component-action package using TableCellsAction, TableCellsRecipe, TableRow, TableBuilder, TableComponentUtil, and TableTypes.
---

# Table Component Action

## When to use this skill

Use this skill when the user needs to:

- Render CRUD or listing rows as backend components.
- Build a complete table from `TableCellsAction` output.
- Configure cell labels, values, themes, attributes, callbacks, or extra cells.
- Use the builder with Flux, Slate, or custom component enums.
- Add Livewire row keys such as `wire:key`.

## Core concepts

`TableCellsAction` produces `<td>` components. The output is a `DataContainer`
keyed by the cell label, in CRUD input order. `TableRow::make()` wraps that
output in a `<tr>` component, and `TableBuilder` assembles the table around the
prebuilt rows.

Never pass the action's cells to the vendor `TableUtil`, it wraps content in a
new `<td>` and produces double-wrapped cells.

## Row action

Create one action per model and execute the inputs through a CRUD collection so
`prepare()` and `cleanup()` run:

```php
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

$action = new TableCellsAction(model: $model);

$cells = $crud->execute($action); // DataContainer keyed by label
$headers = $action->getHeaders(); // ordered labels
```

Executing a single input does not run `cleanup()`:

```php
$action = new TableCellsAction(model: $model);
$action->execute(
    (new DefaultInput('name'))->setRecipe(new TableCellsRecipe(label: 'Name')),
);

$cells = $action->getOutput();
```

### Recipe options

```php
new TableCellsRecipe(
    label: 'Name',
    value: fn ($value, $model) => strtoupper($model->name),
    themeManager: null,
    themes: ['table' => ['td', 'td-dark']],
    attributes: ['class' => 'p-2'],
    component: MainBackendComponent::class,
    type: ComponentEnum::TD,
    callback: fn ($value, $component) => $component->setAttribute('data-name', $value),
);
```

Action-level `themes` and `attributes` act as fallbacks when the recipe has
none. Recipe values take precedence.

Scalar, enum, and `Stringable` values are normalized to strings, including
floats and booleans. Arrays and unsupported objects throw an exception.

### Extra cells

Extra cells are added during `cleanup()`, so run them through a CRUD
collection:

```php
$action->setExtraCell('Settings', new TableCellsRecipe(
    value: fn ($value, $model) => $settingsComponent,
));

$action->setExtraCells([
    'Highlights' => new TableCellsRecipe(value: fn ($value, $model) => $highlightsButton),
]);
```

## Building the table

Cell order comes from the CRUD inputs. Row order comes from the collection.
The builder never reorders either one.

```php
use Juaniquillo\TableComponentAction\Utils\TableBuilder;
use Juaniquillo\TableComponentAction\Utils\TableRow;

$headers = null;
$rows = [];

foreach ($models as $key => $model) {
    $action = new TableCellsAction(model: $model);
    $output = $crud->execute($action)->toArray();

    $headers ??= $action->getHeaders();

    $rows[] = TableRow::make(
        output: $output,
        attributes: ['wire:key' => 'row-'.$key],
    );
}

$table = TableBuilder::make(rows: $rows, headers: $headers)
    ->tableThemes(['table' => 'table'])
    ->tableAttributes(['class' => 'min-w-full'])
    ->getComponent();
```

```blade
{{ $table }}
```

### Headers and validation

- Headers are required when rows exist. They describe the `<thead>` only.
- Headers can be labels or prebuilt `<th>` components.
- A row whose cell count differs from the header count throws an exception.
- An empty row set returns `null` from `components()`, `getComponent()`, and
  `toArray()`.

```php
$table = TableBuilder::make(
    rows: $rows,
    headers: ['Name', 'Email', 'Actions'],
)->getComponent();
```

### Themes per part

```php
$table = TableBuilder::make(rows: $rows, headers: $headers)
    ->tableThemes(['table' => 'table'])
    ->headThemes(['table' => 'thead'])
    ->headerThemes(['table' => ['th', 'th-dark']])
    ->bodyThemes(['table' => 'tbody'])
    ->getComponent();
```

Row themes belong to the row factory:

```php
$rows[] = TableRow::make(
    output: $output,
    attributes: ['wire:key' => 'row-'.$key],
    themes: ['table' => 'tr'],
);
```

## Custom component packages

Use `TableTypes` with a configured `TableComponentUtil` for Flux, Slate, or any
other component package. Pass the same pair to `TableRow::make()` and
`TableBuilder::make()`:

```php
use Juaniquillo\TableComponentAction\Utils\TableComponentUtil;
use Juaniquillo\TableComponentAction\Utils\TableTypes;

$util = new TableComponentUtil(component: FluxBackendComponent::class);

$types = new TableTypes(
    table: FluxComponentEnum::TABLE,
    head: FluxComponentEnum::THEAD,
    body: FluxComponentEnum::TBODY,
    row: FluxComponentEnum::TR,
    header: FluxComponentEnum::TH,
    cell: FluxComponentEnum::TD,
);

$row = TableRow::make(output: $output, util: $util, types: $types);

$table = TableBuilder::make(
    rows: $rows,
    headers: $headers,
    util: $util,
    types: $types,
)->getComponent();
```

## Low-level util

Use `TableComponentUtil` directly when only the parts are needed:

```php
use Juaniquillo\TableComponentAction\Utils\TableComponentUtil;

$util = new TableComponentUtil;

$cells = $crud->execute($action); // DataContainer keyed by label
$labels = $action->getHeaders();

$table = $util->table([
    $util->head(array_map(
        fn (string $label) => $util->header($label),
        $labels,
    )),
    $util->body([
        $util->row($cells->toArray()),
    ]),
]);
```

`row()` ignores cell keys and keeps insertion order, so pass `$cells->toArray()`
directly.

`head()` wraps headers in a `<tr>` using `ComponentEnum::TR` by default. Pass
`rowType:` when using another component enum set.

## Guardrails

- Use `{{ $table }}` in Blade. Do not use `echo` or `{!! !!}`.
- Do not double-wrap action cells in another `<td>`.
- Do not instantiate `MainBackendComponent` for table parts outside the util.
- Do not reorder cells in the builder. CRUD inputs define cell order.
- Keep `TableBuilder` free of Eloquent and CRUD logic.
- Add `wire:key` to rows when rendering Livewire lists.
- Run `composer qa` after changes.
