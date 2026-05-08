<?php

namespace App\Cruds\Squema\ResumeExport;

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
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
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
        // No specific inputs needed for starting a JSON export
        return [];
    }

    public function tableOptions(TableRowsAction $action): void
    {
        $action->setExtraCell('Status', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeExport $export */
                $export = $model;

                $color = match ($export->status) {
                    'pending' => 'zinc',
                    'processing' => 'blue',
                    'completed' => 'green',
                    'failed' => 'red',
                    default => 'zinc',
                };

                $badge = FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                    ->setAttributes([
                        'color' => $color,
                        'inset' => 'top bottom',
                    ])
                    ->setContent(ucfirst($export->status));

                if ($export->status === 'failed' && $export->error) {
                    return TableHelpers::errorTooltip($export->error, $badge);
                }

                return $badge;
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

                if ($export->status === 'completed') {
                    $contents[] = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                        ->setAttribute('href', route('dashboard.resume.export.download', [$export->uuid]))
                        ->setContent('Download')
                        ->setAttribute('size', 'xs')
                        ->setAttribute('variant', 'primary')
                        ->setTheme('cursor', 'pointer');
                }

                if ($export->status === 'failed' && $export->error) {
                    $button = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                        ->setAttribute('size', 'xs')
                        ->setAttribute('variant', 'danger')
                        ->setAttribute('icon', 'information-circle')
                        ->setTheme('cursor', 'help')
                        ->setContent('Error Info');

                    $contents[] = TableHelpers::errorTooltip($export->error, $button, 'left');
                }

                if (empty($contents)) {
                    return '';
                }

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setTheme('display', 'flex')
                    ->setTheme('flex', ['gap-sm']);
            }
        ));
    }

    public function formWithButtonOnly(): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setTheme('cursor', 'pointer')
            ->setContent(__('Start New JSON Export'));
    }
}
