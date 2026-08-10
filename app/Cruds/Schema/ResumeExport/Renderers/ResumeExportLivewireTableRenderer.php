<?php

namespace App\Cruds\Schema\ResumeExport\Renderers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Enums\ProcessStatus;
use App\Livewire\Resume\Export\DeleteResumeExport;
use App\Livewire\Resume\Export\EditResumeExport;
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumeExportLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderDateCell(Model $model): BackendComponent|CompoundComponent
    {
        /** @var ResumeExport $model */
        return ComponentBuilder::make(ComponentEnum::SPAN)
            ->setContent($model->created_at->diffForHumans());
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var ResumeExport $export */
        $export = $model;

        $helper = TableHelpers::make();

        $contents = [];

        $contents[] = $helper->liveWireComponent(
            component: EditResumeExport::class,
            id: "edit-resume-export-{$export->id}",
            params: [$export->id]
        );

        if ($export->status === ProcessStatus::COMPLETED) {
            $enum = $export->type;
            $filename = str_replace(' ', '-', strtolower($export->user->name)).'-resume.'.$enum->extension();

            $contents[] = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                ->setAttribute('href', route('dashboard.resume.export.download', [
                    'uuid' => $export->uuid,
                    'v' => md5($export->created_at),
                ]))
                ->setAttribute('download', $filename)
                ->setContent(__('Download'))
                ->setAttribute('size', 'xs')
                ->setAttribute('variant', 'primary')
                ->setAttribute('icon', 'arrow-down-on-square')
                ->setTheme('cursor', 'pointer');
        }

        if ($export->status === ProcessStatus::FAILED && $export->error) {
            $contents[] = TableHelpers::tableModal(
                id: "error-modal-{$export->id}",
                content: LocalThemeComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent($export->error)
                    ->setTheme('spacing', 'p-top-sm')
                    ->setTheme('text', 'nl2br'),
                heading: 'Export Error Details',
                triggerType: 'danger',
                buttonLabel: 'Error Info'
            );
        }

        if (in_array($export->status, [ProcessStatus::COMPLETED, ProcessStatus::FAILED])) {
            $contents[] = $helper->liveWireComponent(
                component: DeleteResumeExport::class,
                id: "delete-resume-export-{$export->id}",
                params: [$export->id]
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
        return [];
    }
}
