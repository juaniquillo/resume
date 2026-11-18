<?php

namespace App\Cruds\Schema\Volunteers\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class VolunteersTableRenderer implements TableRenderer
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

        $extraCells = $this->renderExtraCells();
        if (! empty($extraCells)) {
            $action->setExtraCells($extraCells);
        }
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Volunteer $volunteer */
        $volunteer = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.certificates.edit', [$volunteer->id])),
            $helper->deleteButton(route('dashboard.certificates.destroy', [$volunteer->id])),
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
                    /** @var Volunteer $volunteer */
                    $volunteer = $model;

                    return TableHelpers::highlightsButton(route('dashboard.volunteers.highlights', [$volunteer->id]));
                },
            ),
        ];
    }
}
