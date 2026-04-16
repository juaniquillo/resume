<?php

namespace App\Cruds\Squema\Works;

use App\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Works\Inputs\EndsAtFactory;
use App\Cruds\Squema\Works\Inputs\NameFactory;
use App\Cruds\Squema\Works\Inputs\PositionFactory;
use App\Cruds\Squema\Works\Inputs\StartsAtFactory;
use App\Cruds\Squema\Works\Inputs\SummaryFactory;
use App\Cruds\Squema\Works\Inputs\UserFactory;
use App\Cruds\Squema\Works\Inputs\UuidFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\MainBackendComponent;

class WorksCrud implements CrudInterface
{
    use IsCrud, 
        HasHtmlTable;

    public function inputsArray(): array
    {
        return [
            'uid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'name' => NameFactory::make(),
            'position' => PositionFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
    ) {}

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new static(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    public function formAction(): string
    {
        return route('dashboard.works.edit');
    }

    public function formWithTextareaSpanFull(?array $values = null, ?array $errors = null, ?Model $model = null): MainBackendComponent
    {
        $inputs = self::inputsArray();
        $summary = $inputs['summary'] ?? null;

        // Textarea input with column span full theme
        $inputs['summary'] = $this->spanFullContainer([
            $summary,
        ]);

        return $this->form(
            inputs: $inputs,
        );
    }
}
