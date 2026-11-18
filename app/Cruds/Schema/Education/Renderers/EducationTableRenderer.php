<?php

namespace App\Cruds\Schema\Education\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\FluxBackendComponents\Factories\FluxComponentFactory;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class EducationTableRenderer implements TableRenderer
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

        /** @var Education $education */
        $education = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.certificates.edit', [$education->id])),
            $helper->deleteButton(route('dashboard.certificates.destroy', [$education->id])),
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
            'Courses' => new TableCellsRecipe(
                value: function ($value, Model $model) {

                    /** @var Education $education */
                    $education = $model;

                    return FluxComponentFactory::make(FluxComponentEnum::BUTTON)
                        ->setContent(
                            FluxComponentFactory::make('icon.building-library')
                                ->setAttribute('variant', 'micro')
                        )
                        ->setContent('Courses')
                        ->setTheme('cursor', 'pointer')
                        ->setAttribute('variant', 'primary')
                        ->setAttribute('color', 'blue')
                        ->setAttribute('size', 'xs')
                        ->setAttribute('wire:navigate', '')
                        ->setAttribute('href', route('dashboard.education.courses', [$education->id]));
                }
            ),
        ];
    }
}
