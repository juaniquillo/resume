<?php

namespace App\Cruds\Schema\ResumeImport;

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
use App\Cruds\Schema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Schema\ResumeImport\Inputs\NameFactory;
use App\Cruds\Schema\ResumeImport\Renderers\ResumeImportLivewireFormRenderer;
use App\Cruds\Schema\ResumeImport\Renderers\ResumeImportLivewireTableRenderer;
use App\Enums\ProcessStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ResumeImportCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'resume_import';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected ?FormRenderer $formRenderer = null,
        protected ?TableRenderer $tableRenderer = null,
    ) {
        $this->formRenderer = $formRenderer ?? ResumeImportLivewireFormRenderer::make();
        $this->tableRenderer = $tableRenderer ?? ResumeImportLivewireTableRenderer::make();
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
            JsonFileFactory::NAME => JsonFileFactory::make(),
        ];
    }

    public function inputsUpdateArray(): array
    {
        return [
            'name' => NameFactory::make(),
        ];
    }

    public function form(): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => $this->tableRenderer->renderSettings($model)
        );

        $action->setExtraCell('Settings', $recipe);
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCells($this->tableRenderer->renderExtraCells());
    }

    public function saveButton(string $label = ''): BackendComponent|CompoundComponent
    {
        $label = ! $label ? $this->saveButtonLabel : $label;
        $livewireAttributes = LivewireHelpers::getLivewireAttributes(JsonFileFactory::NAME, self::getLivewireGroup());

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

    public static function canShowDeleteButton(ProcessStatus $status): bool
    {
        return $status !== ProcessStatus::PENDING && $status !== ProcessStatus::PROCESSING;
    }
}
