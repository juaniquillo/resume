<?php

namespace App\Cruds\Squema\ResumeImport;

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
use App\Cruds\Squema\ResumeImport\Inputs\JsonFileFactory;
use App\Models\ResumeImport;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumeImportCrud implements CrudForm, CrudInterface, CrudTable
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
            JsonFileFactory::NAME => JsonFileFactory::make(),
        ];
    }

    public function tableOptions(TableRowsAction $action): void
    {
        $action->setExtraCell('Resume JSON File', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeImport $import */
                $import = $model;

                return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('href', route('dashboard.resume.import.download', [$import->id]))
                    ->setContent($import->file_name)
                    ->setAttribute('variant', 'ghost')
                    ->setAttribute('size', 'sm')
                    ->setAttribute('icon', 'document-arrow-down')
                    ->setTheme('cursor', 'pointer');
            }
        ));

        $action->setExtraCell('Status', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeImport $import */
                $import = $model;

                $color = match ($import->status) {
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
                    ->setContent(ucfirst($import->status));

                return $badge;
            }
        ));

        $action->setExtraCell('Date', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeImport $model */
                return $model->created_at->diffForHumans();
            }
        ));

        $action->setExtraCell('Actions', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var ResumeImport $import */
                $import = $model;

                $contents = [];

                if ($import->status === 'failed' && $import->error) {
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

                $contents[] = TableHelpers::deleteButton(route('dashboard.resume.import.destroy', $import->id));

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setThemes([
                        'display' => 'flex',
                        'flex' => ['gap-sm'],
                    ]);
            }
        ));
    }

    public function formWithUploadSpanFull(?array $inputs = null): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs([JsonFileFactory::NAME]);
    }
}
