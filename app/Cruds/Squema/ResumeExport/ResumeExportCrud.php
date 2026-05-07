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
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

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

                return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                    ->setAttributes([
                        'color' => $color,
                        'inset' => 'top bottom',
                    ])
                    ->setContent(ucfirst($export->status));
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

                if ($export->status !== 'completed') {
                    return '';
                }

                return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('href', route('dashboard.resume.export.download', [$export->uuid]))
                    ->setContent('Download')
                    ->setAttribute('size', 'xs')
                    ->setAttribute('variant', 'primary')
                    ->setTheme('cursor', 'pointer');
            }
        ));
    }

    public function formWithButtonOnly(): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setContent('Start New JSON Export');
    }
}
