<?php

namespace App\Cruds\Schema\Projects\Renderers;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

final class ProjectsTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function tableActionInstance(TableRowsAction $action): void
    {
        $action->setExtraCell('Settings', new TableRowsRecipe(
            value: fn ($value, $model) => $this->renderSettings($model)
        ));

        $extraCells = $this->renderExtraCells();
        if (! empty($extraCells)) {
            $action->setExtraCells($extraCells);
        }
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Project $project */
        $project = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.projects.edit', [$project->id])),
            $helper->deleteButton(route('dashboard.projects.destroy', [$project->id])),
        ];

        return ComponentFactory::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }

    public function renderExtraCells(): array
    {
        // Implementation for rendering extra cells
        return [
            'Highlights' => new TableRowsRecipe(
                value: function ($value, Model $model) {
                    /** @var Project $project */
                    $project = $model;

                    return TableHelpers::highlightsButton(route('dashboard.projects.highlights', [$project->id]));
                },
            ),
        ];
    }
}
