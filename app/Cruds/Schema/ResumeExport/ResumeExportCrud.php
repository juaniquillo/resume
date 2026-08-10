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
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\ResumeExport\Inputs\AllowDownloadSwitchFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportThemeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportTypeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\NameFactory;
use App\Cruds\Schema\ResumeExport\Inputs\StatusFactory;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportLivewireFormRenderer;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportLivewireTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ResumeExportCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'resume_export';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected ?FormRenderer $formRenderer = null,
        protected ?TableRenderer $tableRenderer = null,
    ) {
        $this->formRenderer = $formRenderer ?? ResumeExportLivewireFormRenderer::make();
        $this->tableRenderer = $tableRenderer ?? ResumeExportLivewireTableRenderer::make();
    }

    public static function build(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
        ?FormRenderer $formRenderer = null,
        ?TableRenderer $tableRenderer = null,
    ): static {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
            formRenderer: $formRenderer,
            tableRenderer: $tableRenderer,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    public function inputsArray(): array
    {
        return [
            'name' => NameFactory::make(),
            'type' => ExportTypeSelectFactory::make(),
            'theme' => ExportThemeSelectFactory::make(),
            'allow_download' => AllowDownloadSwitchFactory::make(),
            'status' => StatusFactory::make(),
        ];
    }

    public function inputsUpdateArray(): array
    {
        return [
            'name' => NameFactory::make(),
            'allow_download' => AllowDownloadSwitchFactory::make(),
        ];
    }

    public function form(): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public function formNarrow(): BackendComponent|CompoundComponent
    {
        return $this->form();
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->form();
    }

    public function tableOptions(TableRowsAction $action): void
    {
        /** @var ResumeExportLivewireTableRenderer $renderer */
        $renderer = $this->tableRenderer;

        $action->setExtraCell('Actions', new TableRowsRecipe(
            value: fn ($value, Model $model) => $renderer->renderSettings($model)
        ));
    }

    public function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCells($this->tableRenderer->renderExtraCells());
    }

    public function saveButton(string $label = 'Start New Export'): BackendComponent|CompoundComponent
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes('type', self::getLivewireGroup());

        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setAttributes([
                'wire:loading.attr' => 'disabled',
                'wire:target' => $livewireAttributes['wire:model'] ?? 'createForm',
            ])
            ->setTheme('cursor', 'pointer')
            ->setContent(__($label));
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'two-column',
        ];
    }
}
