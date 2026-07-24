<?php

namespace App\Cruds\Schema\ResumeExport;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Schema\ResumeExport\Inputs\AllowDownloadSwitchFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportThemeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportTypeSelectFactory;
use App\Enums\ProcessStatus;
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumeExportCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
    ) {}

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    public function inputsArray(): array
    {
        return [
            'type' => ExportTypeSelectFactory::make(),
            'theme' => ExportThemeSelectFactory::make(),
            'allow_download' => AllowDownloadSwitchFactory::make(),
        ];
    }

    public function tableOptions(TableRowsAction $action): void
    {

        $action->setExtraCell('Status', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeExport $export */
                $export = $model;

                return TableHelpers::statusBadge($export->status);
            }
        ));

        $action->setExtraCell('Date', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeExport $model */
                return $model->created_at->diffForHumans();
            }
        ));

        $action->setExtraCell('Actions', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeExport $export */
                $export = $model;

                $contents = [];

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
                    $contents[] = TableHelpers::deleteButton(route('dashboard.resume.export.destroy', $export->id));
                }

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setThemes([
                        'display' => 'flex',
                        'flex' => ['gap-sm'],
                    ]);
            }
        ));
    }

    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setTheme('cursor', 'pointer')
            ->setContent(__('Start New Export'));
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'two-column',
        ];
    }
}




