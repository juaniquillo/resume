<?php

namespace App\Cruds\Schema\Highlights;

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
use App\Cruds\Schema\Highlights\Inputs\HighlightFactory;
use App\Cruds\Schema\Highlights\Renderers\HighlightsFormRenderer;
use App\Cruds\Schema\Highlights\Renderers\HighlightsTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class HighlightsCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'highlights';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected ?string $baseRoute = null,
        protected FormRenderer $formRenderer = new HighlightsFormRenderer,
        protected TableRenderer $tableRenderer = new HighlightsTableRenderer,
    ) {}

    public static function build(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
        ?string $baseRoute = null,
        ?FormRenderer $formRenderer = null,
        ?TableRenderer $tableRenderer = null,
    ): static {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
            baseRoute: $baseRoute,
            formRenderer: $formRenderer ?? new HighlightsFormRenderer,
            tableRenderer: $tableRenderer ?? new HighlightsTableRenderer,
        );
    }

    public function setBaseRoute(string $baseRoute): static
    {
        $this->baseRoute = $baseRoute;

        return $this;
    }

    public function inputsArray(): array
    {
        return [
            'highlight' => HighlightFactory::make(),
        ];

    }

    public function form(?array $inputs = null): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
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

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}
