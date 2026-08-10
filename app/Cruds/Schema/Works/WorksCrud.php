<?php

namespace App\Cruds\Schema\Works;

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
use App\Cruds\Schema\Works\Inputs\EndsAtFactory;
use App\Cruds\Schema\Works\Inputs\NameFactory;
use App\Cruds\Schema\Works\Inputs\PositionFactory;
use App\Cruds\Schema\Works\Inputs\StartsAtFactory;
use App\Cruds\Schema\Works\Inputs\SummaryFactory;
use App\Cruds\Schema\Works\Inputs\UrlFactory;
use App\Cruds\Schema\Works\Inputs\UserFactory;
use App\Cruds\Schema\Works\Inputs\UuidFactory;
use App\Cruds\Schema\Works\Renderers\WorksFormRenderer;
use App\Cruds\Schema\Works\Renderers\WorksTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

final class WorksCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'works';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected FormRenderer $formRenderer = new WorksFormRenderer,
        protected TableRenderer $tableRenderer = new WorksTableRenderer,
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
            formRenderer: $formRenderer ?? new WorksFormRenderer,
            tableRenderer: $tableRenderer ?? new WorksTableRenderer,
        );
    }

    public function inputsArrayComplete(): array
    {
        return [
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            ...$this->inputsArray(),
        ];
    }

    /** @return array<?InputInterface> */
    public function inputsArray(): array
    {
        return [
            NameFactory::NAME => NameFactory::make(),
            UrlFactory::NAME => UrlFactory::make(),
            PositionFactory::NAME => PositionFactory::make(),
            StartsAtFactory::NAME => StartsAtFactory::make(),
            EndsAtFactory::NAME => EndsAtFactory::make(),
            SummaryFactory::NAME => SummaryFactory::make(),
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

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->form();
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCells($this->tableRenderer->renderExtraCells());
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

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}
