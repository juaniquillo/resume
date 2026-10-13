<?php

namespace App\Cruds\Schema\Education\Renderers;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\FluxBackendComponents\Builders\FluxComponentBuilder;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

final class EducationTableRenderer implements TableRenderer
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

        /** @var Education $education */
        $education = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.certificates.edit', [$education->id])),
            $helper->deleteButton(route('dashboard.certificates.destroy', [$education->id])),
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
            'Courses' => new TableRowsRecipe(
                value: function ($value, Model $model) {

                    /** @var Education $education */
                    $education = $model;

                    return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                        ->setContent(
                            FluxComponentBuilder::make('icon.building-library')
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
