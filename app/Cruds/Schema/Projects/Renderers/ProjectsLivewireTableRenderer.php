<?php

namespace App\Cruds\Schema\Projects\Renderers;

use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Projects\DeleteProject;
use App\Livewire\Resume\Projects\EditProject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ProjectsLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Project $project */
        $project = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditProject::class,
                id: "edit-project-{$project->id}",
                params: [$project->id]
            ),
            $helper->liveWireComponent(
                component: DeleteProject::class,
                id: "delete-project-{$project->id}",
                params: [$project->id]
            ),
        ];

        return ComponentBuilder::make(ComponentEnum::DIV)
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
            )
        ];
    }
}
