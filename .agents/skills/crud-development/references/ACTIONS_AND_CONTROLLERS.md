# Actions & Controllers

We follow the "Thick Actions, Thin Controllers" pattern to ensure business logic is reusable and testable.

## Actions

Place actions in `app/Actions/Resume/{Entity}/`. Use PHP 8.4 property promotion.

```php
<?php

namespace App\Actions\Resume\MyEntity;

use App\Models\User;
use App\Models\MyEntity;

class CreateMyEntity
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): MyEntity
    {
        return $this->user->myEntities()->create($this->data);
    }
}
```

## Controllers

Controllers orchestration the CRUD schema and the Actions.

```php
<?php

namespace App\Http\Controllers;

use App\Cruds\Schema\MyEntity\MyEntityCrud;
use App\Http\Requests\MyEntityFormRequest;
use App\Actions\Resume\MyEntity\CreateMyEntity;

class MyEntityController extends Controller
{
    public function index(Request $request)
    {
        $crud = MyEntityCrud::build();
        return view('dashboard.my-entities.index', [
            'form' => $crud->form(),
            'table' => $crud->makeTable($request->user()->myEntities),
        ]);
    }

    public function store(MyEntityFormRequest $request)
    {
        (new CreateMyEntity($request->validated(), $request->user()))->handle();
        return back()->with('notify', ['message' => 'Created successfully!']);
    }
}
```

## Form Requests

Generate validation logic directly from the CRUD schema.

```php
public function rules(): array
{
    return MyEntityCrud::build()->make()
        ->execute(new LaravelValidationRulesAction)
        ->toArray();
}
```





