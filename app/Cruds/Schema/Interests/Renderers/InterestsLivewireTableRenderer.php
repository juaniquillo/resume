<?php

namespace App\Cruds\Schema\Interests\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Interests\DeleteInterest;
use App\Livewire\Resume\Interests\EditInterest;
use App\Models\Interest;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class InterestsLivewireTableRenderer implements TableRenderer
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
        /** @var Interest $interest */
        $interest = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditInterest::class,
                id: "edit-interest-{$interest->id}",
                params: [$interest->id]
            ),
            $helper->liveWireComponent(
                component: DeleteInterest::class,
                id: "delete-interest-{$interest->id}",
                params: [$interest->id]
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
