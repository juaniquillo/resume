<?php

namespace App\Cruds\Schema\Volunteers;

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
use App\Cruds\Schema\Volunteers\Inputs\EndsAtFactory;
use App\Cruds\Schema\Volunteers\Inputs\OrganizationFactory;
use App\Cruds\Schema\Volunteers\Inputs\PositionFactory;
use App\Cruds\Schema\Volunteers\Inputs\StartsAtFactory;
use App\Cruds\Schema\Volunteers\Inputs\SummaryFactory;
use App\Cruds\Schema\Volunteers\Inputs\UrlFactory;
use App\Cruds\Schema\Volunteers\Inputs\UserFactory;
use App\Cruds\Schema\Volunteers\Inputs\UuidFactory;
use App\Cruds\Schema\Volunteers\Renderers\VolunteersFormRenderer;
use App\Cruds\Schema\Volunteers\Renderers\VolunteersTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Override;

final class VolunteersCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected FormRenderer $formRenderer = new VolunteersFormRenderer,
        protected TableRenderer $tableRenderer = new VolunteersTableRenderer,
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
            formRenderer: $formRenderer ?? new VolunteersFormRenderer,
            tableRenderer: $tableRenderer ?? new VolunteersTableRenderer,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel('volunteers');
    }

    public function inputsArray(): array
    {
        return [
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'organization' => OrganizationFactory::make(),
            'position' => PositionFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'url' => UrlFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    #[Override]
    public function form(): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
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
}
