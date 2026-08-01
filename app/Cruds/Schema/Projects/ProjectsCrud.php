<?php

namespace App\Cruds\Schema\Projects;

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
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Schema\Projects\Inputs\DescriptionFactory;
use App\Cruds\Schema\Projects\Inputs\EndDateFactory;
use App\Cruds\Schema\Projects\Inputs\NameFactory;
use App\Cruds\Schema\Projects\Inputs\StartDateFactory;
use App\Cruds\Schema\Projects\Inputs\UrlFactory;
use App\Cruds\Schema\Projects\Inputs\UserFactory;
use App\Cruds\Schema\Projects\Inputs\UuidFactory;
use App\Cruds\Schema\Projects\Renderers\ProjectsFormRenderer;
use App\Cruds\Schema\Projects\Renderers\ProjectsTableRenderer;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ProjectsCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'projects';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected FormRenderer $formRenderer = new ProjectsFormRenderer,
        protected TableRenderer $tableRenderer = new ProjectsTableRenderer,
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
            formRenderer: $formRenderer ?? new ProjectsFormRenderer,
            tableRenderer: $tableRenderer ?? new ProjectsTableRenderer,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    public function inputsArray(): array
    {
        return [
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'name' => NameFactory::make(),
            'start_date' => StartDateFactory::make(),
            'end_date' => EndDateFactory::make(),
            'url' => UrlFactory::make(),
            'description' => DescriptionFactory::make(),
        ];
    }

    public function form(?array $inputs = null): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public function formNarrow(): BackendComponent|CompoundComponent
    {
        return $this->form();
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['description']);
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCell('Highlights', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var Project $project */
                $project = $model;

                return TableHelpers::highlightsButton(route('dashboard.projects.highlights', [$project->id]));
            },
        ));
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
