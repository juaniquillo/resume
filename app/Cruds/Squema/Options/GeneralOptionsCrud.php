<?php

namespace App\Cruds\Squema\Options;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Options\Inputs\SlugFactory;
use App\Cruds\Squema\Options\Inputs\ThemeSelectFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Override;

final class GeneralOptionsCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'general_options';

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

    #[Override]
    public function inputsArray(): array
    {
        /** @var User|null $user */
        $user = $this->model;

        return [
            SlugFactory::NAME => SlugFactory::make($user?->id),
            ThemeSelectFactory::NAME => ThemeSelectFactory::make(),
        ];
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'one-column',
        ];
    }
}
