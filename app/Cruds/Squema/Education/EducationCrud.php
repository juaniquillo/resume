<?php

namespace App\Cruds\Squema\Education;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Education\Inputs\AreaFactory;
use App\Cruds\Squema\Education\Inputs\EndsAtFactory;
use App\Cruds\Squema\Education\Inputs\InstitutionFactory;
use App\Cruds\Squema\Education\Inputs\ScoreFactory;
use App\Cruds\Squema\Education\Inputs\StartsAtFactory;
use App\Cruds\Squema\Education\Inputs\StudyTypeFactory;
use App\Cruds\Squema\Education\Inputs\UrlFactory;
use App\Cruds\Squema\Education\Inputs\UserFactory;
use Illuminate\Database\Eloquent\Model;

final class EducationCrud implements CrudInterface
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
            'institution' => InstitutionFactory::make(),
            'url' => UrlFactory::make(),
            'area' => AreaFactory::make(),
            'study_type' => StudyTypeFactory::make(),
            'score' => ScoreFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
        ];
    }

    public function formAction(): string
    {
        return route('dashboard.education.store');
    }
}
