<?php

namespace App\Cruds\Schema\Languages;

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
use App\Cruds\Schema\Languages\Inputs\FluencyFactory;
use App\Cruds\Schema\Languages\Inputs\LanguageFactory;
use App\Cruds\Schema\Languages\Inputs\UserFactory;
use App\Cruds\Schema\Languages\Inputs\UuidFactory;
use App\Cruds\Schema\Languages\Renderers\LanguagesFormRenderer;
use App\Cruds\Schema\Languages\Renderers\LanguagesTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class LanguagesCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'languages';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected FormRenderer $formRenderer = new LanguagesFormRenderer,
        protected TableRenderer $tableRenderer = new LanguagesTableRenderer,
    ) {}

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
            formRenderer: $formRenderer ?? new LanguagesFormRenderer,
            tableRenderer: $tableRenderer ?? new LanguagesTableRenderer,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    public function inputsArray(): array
    {
        return [
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'language' => LanguageFactory::make(),
            'fluency' => FluencyFactory::make(),
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

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => $this->tableRenderer->renderSettings($model)
        );

        $action->setExtraCell('Settings', $recipe);
    }
}
