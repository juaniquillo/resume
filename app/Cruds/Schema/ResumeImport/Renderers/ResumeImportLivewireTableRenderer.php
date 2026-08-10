<?php

namespace App\Cruds\Schema\ResumeImport\Renderers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
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

    // public function renderFileCell(Model $model): BackendComponent|CompoundComponent
    // {
    //     /** @var ResumeImport $import */
    //     $import = $model;

    //     return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
    //         ->setAttribute('href', route('dashboard.resume.import.download', [$import->id]))
    //         ->setContent($import->file_name)
    //         ->setAttribute('variant', 'ghost')
    //         ->setAttribute('size', 'sm')
    //         ->setAttribute('icon', 'document-arrow-down')
    //         ->setTheme('cursor', 'pointer');
    // }

    // public function renderStatusCell(Model $model): BackendComponent|CompoundComponent
    // {
    //     /** @var ResumeImport $import */
    //     $import = $model;

    //     return TableHelpers::statusBadge($import->status);
    // }

    // public function renderDateCell(Model $model): BackendComponent|CompoundComponent
    // {
    //     /** @var ResumeImport $model */
    //     return ComponentBuilder::make(ComponentEnum::SPAN)
    //         ->setContent($model->created_at->diffForHumans());
    // }

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

        ];
    }
}
