<?php

namespace App\Cruds\Squema\Options;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Options\Inputs\SlugFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class UserSlugCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'user_slug';

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
        /** @var User|null $model */
        $model = $this->model;

        return [
            'slug' => SlugFactory::make($model?->id),
        ];
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'one-column',
        ];
    }
}
