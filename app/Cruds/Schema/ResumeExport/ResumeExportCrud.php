<?php

namespace App\Cruds\Schema\ResumeExport;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Cruds\Schema\ResumeExport\Inputs\AllowDownloadSwitchFactory;
use App\Cruds\Schema\ResumeExport\Inputs\CustomOptionsFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportThemeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportTypeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\NameFactory;
use App\Cruds\Schema\ResumeExport\Inputs\StatusFactory;
use App\Cruds\Schema\ResumeExport\Inputs\UseCustomGeneralOptionsFactory;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportLivewireTableRenderer;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportUpdateLivewireFormRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\InputComponentAction;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

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
    ) {}

    public static function build(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
        ?FormRenderer $formRenderer = new ResumeExportUpdateLivewireFormRenderer,
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
            $this->separator('export_options_1'),

            'custom_options' => CustomOptionsFactory::make(),

            /** General options */
            $this->generalOptionsGroup(),

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

    public function generalOptionsGroup()
    {
        $customOptionsInputs = GeneralOptionsCrud::build()->optionsInputsArray();

        $options = CrudAssistant::make([
            'custom_options' => $this->fieldsetWrap(
                inputs: [
                    ...$customOptionsInputs,
                ],
                key: 'custom_options',
                legend: 'Custom Options',
                wrapperAttributes: [
                    'x-show' => 'showOptions',
                    'x-cloak' => '',
                ],
                onlyFor: [
                    InputComponentAction::class,
                    LaravelValidationRulesAction::class,
                ],
            ),
        ], 'option_toggle_group')
            ->setType(FormHelpers::IGNORE_LIVEWIRE_BINDINGS);

        //
        $wrapper = CrudAssistant::make([
            'custom_options' => UseCustomGeneralOptionsFactory::make(),
            $options,
        ], 'option_toggle_wrapper')
            ->setType(FormHelpers::FORM_WRAPPER_TYPE)
            ->setRecipe(
                new InputComponentRecipe(
                    themeManager: new LocalThemeManager,
                    themeBag: (new DefaultThemeBag)
                        ->setWrapperTheme([
                            'forms' => 'one-column',
                        ]),
                    attributeBag: (new DefaultAttributeBag)
                        ->setWrapperAttributes([
                            'x-data' => '{ showOptions: false }',
                        ])
                )
            );

        return $wrapper;

    }
}
