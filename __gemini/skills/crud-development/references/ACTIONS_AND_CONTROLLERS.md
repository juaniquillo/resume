# Actions & Controllers

We prefer thick actions and thin controllers.

## Actions

Actions should be placed in `app/Actions/Resume/{Entity}/`.

```php
class CreateWork
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Work
    {
        return $this->user->works()->create($this->data);
    }
}
```

## Controllers

Controllers should use the CRUD schema to render the form/table and the Action to persist.

```php
class WorkController extends Controller
{
    public function index(Request $request)
    {
        $crud = WorkCrud::build(model: new Work);
        return view('dashboard.works.index', [
            'form' => $crud->form(),
            'table' => $crud->makeTable($request->user()->works()->paginate()),
        ]);
    }

    public function store(WorkFormRequest $request)
    {
        (new CreateWork($request->validated(), $request->user()))->handle();
        return back()->with('success', 'Saved!');
    }
}
```

## Form Requests

Use the CRUD schema to generate rules, messages, and attributes automatically.

```php
class WorkFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

    protected function prepareForValidation()
    {
        $this->crud = WorkCrud::build()->make();
    }

    public function rules(): array
    {
        return $this->crud->execute(new LaravelValidationRulesAction)->toArray();
    }
    
    // ... same for messages() and attributes()
}
```
