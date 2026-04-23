<?php

namespace App\Cruds\Squema\Profiles;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Squema\Profiles\Inputs\BasicsFactory;
use App\Cruds\Squema\Profiles\Inputs\NetworkFactory;
use App\Cruds\Squema\Profiles\Inputs\UrlFactory;
use App\Cruds\Squema\Profiles\Inputs\UsernameFactory;
use Illuminate\Database\Eloquent\Model;

final class ProfilesCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

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
            BasicsFactory::make(),
            NetworkFactory::make(),
            UsernameFactory::make(),
            UrlFactory::make(),
        ];
    }

}
