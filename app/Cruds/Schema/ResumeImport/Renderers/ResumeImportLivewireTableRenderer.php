<?php

namespace App\Cruds\Schema\ResumeImport\Renderers;

use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Enums\ProcessStatus;
use App\Livewire\Resume\Import\DeleteResumeImport;
use App\Models\ResumeImport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumeImportLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var ResumeImport $import */
        $import = $model;

        $helper = TableHelpers::make();

        $contents = [];

        if ($import->status === ProcessStatus::FAILED && $import->error) {
            $contents[] = TableHelpers::tableModal(
                id: "error-modal-{$import->id}",
                content: LocalThemeComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent($import->error)
                    ->setTheme('spacing', 'p-top-sm')
                    ->setTheme('text', 'nl2br'),
                heading: 'Import Error Details',
                triggerType: 'danger',
                buttonLabel: 'Error Info'
            );
        }

        if ($import->status !== ProcessStatus::PENDING && $import->status !== ProcessStatus::PROCESSING) {
            $contents[] = $helper->liveWireComponent(
                component: DeleteResumeImport::class,
                id: "delete-resume-import-{$import->id}",
                params: [$import->id]
            );
        }

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
                    /** @var ResumeImport $import */
                    $import = $model;

                    return TableHelpers::statusBadge($import->status);
                },
            ),
            'Created' => new TableRowsRecipe(
                value: function ($value, Model $model) {
                    /** @var ResumeImport $import */
                    $import = $model;

                    return ComponentBuilder::make(ComponentEnum::SPAN)
                        ->setContent($import->created_at->diffForHumans());
                },
            ),

        ];
    }
}
