<?php

namespace App\Cruds\Schema\Works\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Works\DeleteWork;
use App\Livewire\Resume\Works\EditWork;
use App\Models\Work;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class WorksLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function tableActionInstance(TableCellsAction $action): void
    {
        $action->setExtraCell('Settings', new TableCellsRecipe(
            value: fn ($value, $model) => $this->renderSettings($model)
        ));

        $action->setExtraCells($this->renderExtraCells());

    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Work $work */
        $work = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditWork::class,
                id: "edit-work-{$work->id}",
                params: [$work->id]
            ),
            $helper->liveWireComponent(
                component: DeleteWork::class,
                id: "delete-work-{$work->id}",
                params: [$work->id]
            ),
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
            'Highlights' => new TableCellsRecipe(
                value: function ($value, Model $model) {
                    /** @var Work $work */
                    $work = $model;

                    return TableHelpers::highlightsButton(route('dashboard.works.highlights', [$work->id]));
                },
            ),
        ];
    }
}
