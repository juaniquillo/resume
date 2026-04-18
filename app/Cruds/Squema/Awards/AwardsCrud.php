<?php

namespace App\Cruds\Squema\Awards;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Awards\Inputs\AwardedAtFactory;
use App\Cruds\Squema\Awards\Inputs\AwarderFactory;
use App\Cruds\Squema\Awards\Inputs\SummaryFactory;
use App\Cruds\Squema\Awards\Inputs\TitleFactory;
use App\Cruds\Squema\Awards\Inputs\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class AwardsCrud implements CrudInterface
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
            'title' => TitleFactory::make(),
            'awarder' => AwarderFactory::make(),
            'awarded_at' => AwardedAtFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public function formAction(): string
    {
        return route('dashboard.awards.store');
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        $inputs = $this->inputsArray();
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
