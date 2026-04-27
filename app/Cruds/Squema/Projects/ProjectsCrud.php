<?php

namespace App\Cruds\Squema\Projects;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Projects\Inputs\DescriptionFactory;
use App\Cruds\Squema\Projects\Inputs\EndDateFactory;
use App\Cruds\Squema\Projects\Inputs\NameFactory;
use App\Cruds\Squema\Projects\Inputs\StartDateFactory;
use App\Cruds\Squema\Projects\Inputs\UrlFactory;
use App\Cruds\Squema\Projects\Inputs\UserFactory;
use App\Cruds\Squema\Projects\Inputs\UuidFactory;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ProjectsCrud implements CrudForm, CrudInterface, CrudTable
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
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'name' => NameFactory::make(),
            'start_date' => StartDateFactory::make(),
            'end_date' => EndDateFactory::make(),
            'url' => UrlFactory::make(),
            'description' => DescriptionFactory::make(),
        ];
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
            value: function ($value, Model $model) {

                /** @var Project $project */
                $project = $model;

                $helper = TableHelpers::make();

                $contents = [
                    $helper->editButton(route('dashboard.projects.edit', [$project->id])),
                    $helper->deleteButton(route('dashboard.projects.destroy', [$project->id])),
                ];

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setTheme('display', 'flex')
                    ->setTheme('flex', [
                        'gap-sm',
                    ]);
            }
        );

        $action->setExtraCell('Settings', $recipe);
    }

}
