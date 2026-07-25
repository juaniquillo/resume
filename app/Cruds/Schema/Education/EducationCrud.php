<?php

namespace App\Cruds\Schema\Education;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Education\Inputs\AreaFactory;
use App\Cruds\Schema\Education\Inputs\EndsAtFactory;
use App\Cruds\Schema\Education\Inputs\InstitutionFactory;
use App\Cruds\Schema\Education\Inputs\ScoreFactory;
use App\Cruds\Schema\Education\Inputs\StartsAtFactory;
use App\Cruds\Schema\Education\Inputs\StudyTypeFactory;
use App\Cruds\Schema\Education\Inputs\UrlFactory;
use App\Cruds\Schema\Education\Inputs\UserFactory;
use App\Cruds\Schema\Education\Inputs\UuidFactory;
use App\Cruds\Schema\Education\Renderers\EducationFormRenderer;
use App\Cruds\Schema\Education\Renderers\EducationTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\InputComponentAction\InputComponentAction;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

final class EducationCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'education';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected bool $isLivewire = false,
    ) {}

    public function setLivewire(bool $isLivewire = true): static
    {
        $this->isLivewire = $isLivewire;

        return $this;
    }

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel('education');
    }

    public function inputsArray(): array
    {
        $inputs = [
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'institution' => InstitutionFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'area' => AreaFactory::make(),
            'study_type' => StudyTypeFactory::make(),
            'score' => ScoreFactory::make(),
            'url' => UrlFactory::make(),
        ];

        if ($this->isLivewire) {
            foreach ($inputs as $name => $input) {
                /** @var InputComponentRecipe|null $recipe */
                $recipe = $input->getRecipe(InputComponentAction::getIdentifier());

                if ($recipe instanceof InputComponentRecipe && $recipe->getAttributeBag() !== null) {
                    $attributes = LivewireHelpers::getLivewireAttributes($name, self::getLivewireGroup());
                    $attributeBag = $recipe->getAttributeBag();

                    $currentAttributes = $attributeBag->getInputAttributes();
                    $attributeBag->setInputAttributes(array_merge($currentAttributes, $attributes));
                }
            }
        }

        return $inputs;
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return EducationFormRenderer::make()->renderFull($this, ['url']);
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => EducationTableRenderer::make()->renderCourses($model)
        );

        $action->setExtraCell('Courses', $recipe);
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => EducationTableRenderer::make()->renderSettings($model)
        );

        $action->setExtraCell('Settings', $recipe);
    }
}
