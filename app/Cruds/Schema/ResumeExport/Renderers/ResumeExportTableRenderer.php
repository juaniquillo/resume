<?php

namespace App\Cruds\Schema\ResumeExport\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class ResumeExportTableRenderer implements TableRenderer
{
    protected string $settingsLabel = 'Actions';

    public static function make(): static
    {
        return new self;
    }

    public function tableActionInstance(TableCellsAction $action): void
    {
        $action->setExtraCell('Actions', new TableCellsRecipe(
            value: fn ($value, $model) => $this->renderSettings($model)
        ));

    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var ResumeExport $export */
        $export = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.resume.exports.edit', [$export->id])),
        ];

        if ($export->status->completed()) {
            $contents[] = $helper->livewireDeleteButton(
                action: "deleteResumeExport({$export->id})",
                confirmMessage: 'Are you sure you want to delete this export?'
            )->setAttribute('size', 'xs');
        }

        return ComponentFactory::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
