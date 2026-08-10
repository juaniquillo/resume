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
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Contracts\TableRenderer;
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
        protected FormRenderer $formRenderer = new EducationFormRenderer,
        protected TableRenderer $tableRenderer = new EducationTableRenderer,
    ) {}

    public static function build(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
        ?FormRenderer $formRenderer = null,
        ?TableRenderer $tableRenderer = null,
    ): static {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
            formRenderer: $formRenderer ?? new EducationFormRenderer,
            tableRenderer: $tableRenderer ?? new EducationTableRenderer,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel('education');
    }

    public function inputsArray(): array
    {
        return [
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
    }

    public function form(): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->form();
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCells($this->tableRenderer->renderExtraCells());
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => $this->tableRenderer->renderSettings($model)
        );

        $action->setExtraCell('Settings', $recipe);
    }
}
