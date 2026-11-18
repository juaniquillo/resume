<?php

namespace App\Cruds\Schema\Highlights\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Highlights\DeleteHighlight;
use App\Livewire\Resume\Highlights\EditHighlight;
use App\Models\Highlight;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class HighlightsLivewireTableRenderer implements TableRenderer
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
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Highlight $highlight */
        $highlight = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditHighlight::class,
                id: "edit-highlight-{$highlight->id}",
                params: [$highlight->id]
            ),
            $helper->liveWireComponent(
                component: DeleteHighlight::class,
                id: "delete-highlight-{$highlight->id}",
                params: [$highlight->id]
            ),
        ];

        return ComponentFactory::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
