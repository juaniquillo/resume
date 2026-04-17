<?php

namespace App\Cruds\Squema\Highlights;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Highlights\Inputs\HighlightFactory;
use Illuminate\Database\Eloquent\Model;

final class HighlightsCrud implements CrudInterface
{
    use IsCrud;

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
    ) {}

    public function inputsArray(): array
    {
        return [
            HighlightFactory::make(),
        ];
    }

    public function formAction(): string
    {
        return '';
    }
}
