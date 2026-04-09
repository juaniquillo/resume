<?php

namespace App\Cruds\Squema\Volunteers;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Volunteers\Inputs\EndsAtFactory;
use App\Cruds\Squema\Volunteers\Inputs\OrganizationFactory;
use App\Cruds\Squema\Volunteers\Inputs\PositionFactory;
use App\Cruds\Squema\Volunteers\Inputs\StartsAtFactory;
use App\Cruds\Squema\Volunteers\Inputs\SummaryFactory;
use App\Cruds\Squema\Volunteers\Inputs\UrlFactory;
use App\Cruds\Squema\Volunteers\Inputs\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\MainBackendComponent;

final class VolunteersCrud implements CrudInterface
{
    use IsCrud;

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
    ) {}

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    public function inputsArray(): array
    {
        return [
            'user' => UserFactory::make(),
            'organization' => OrganizationFactory::make(),
            'position' => PositionFactory::make(),
            'url' => UrlFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public function formAction(): string
    {
        return route('dashboard.volunteers.edit');
    }

    public function formWithTextareaSpanFull(?array $inputs = null): MainBackendComponent
    {
        $inputs = $inputs ?? $this->inputsArray();
        $summary = $inputs['summary'] ?? null;

        if ($summary) {
            $inputs['summary'] = $this->spanFullContainer([
                $summary,
            ]);
        }

        return $this->form(
            inputs: $inputs,
        );
    }
}
