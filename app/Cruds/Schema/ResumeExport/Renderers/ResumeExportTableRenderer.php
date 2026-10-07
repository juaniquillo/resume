<?php

namespace App\Cruds\Schema\ResumeExport\Renderers;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumeExportTableRenderer implements TableRenderer
{
    protected string $settingsLabel = 'Actions';

    public static function make(): static
    {
        return new self;
    }

    public function tableActionInstance(TableRowsAction $action): void
    {
        $action->setExtraCell('Actions', new TableRowsRecipe(
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

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
