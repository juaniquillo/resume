<?php

namespace App\Cruds\Schema\Highlights;

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
use App\Cruds\Schema\Highlights\Inputs\HighlightFactory;
use App\Cruds\Schema\Highlights\Renderers\HighlightsFormRenderer;
use App\Cruds\Schema\Highlights\Renderers\HighlightsTableRenderer;
use App\Models\Highlight;
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

    private bool $isLivewire = false;

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected ?string $baseRoute = null,
    ) {}

    public function setLivewire(bool $isLivewire = true): static
    {
        $this->isLivewire = $isLivewire;

        return $this;
    }

    public static function build(array $values = [], array $errors = [], ?Model $model = null, ?string $baseRoute = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
            baseRoute: $baseRoute,
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
            'highlight' => HighlightFactory::make($this->isLivewire),
        ];

    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return HighlightsFormRenderer::make()->renderFull($this, ['highlight']);
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => HighlightsTableRenderer::make()->renderSettings($model)
        );

        $action->setExtraCell('Settings', $recipe);
    }

    public function tableEditButton(Highlight $highlight): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', route($this->baseRoute.'.edit', [$highlight->highlightable_id, $highlight->id]))
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}
