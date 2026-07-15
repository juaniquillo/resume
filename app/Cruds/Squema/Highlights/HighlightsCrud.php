<?php

namespace App\Cruds\Squema\Highlights;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Concerns\IsLivewireForm;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Highlights\Inputs\HighlightFactory;
use App\Livewire\Resume\Highlights\DeleteHighlight;
use App\Livewire\Resume\Highlights\EditHighlight;
use App\Models\Highlight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class HighlightsCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud,
        IsLivewireForm;

    public const NAME = 'highlights';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected ?string $baseRoute = null,
    ) {}

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
        return $this->formFullSpanInputs(['highlight']);
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {

                /** @var Highlight $highlight */
                $highlight = $model;

                $helper = TableHelpers::make();

                $contents = [
                    // $this->tableEditButton($highlight),
                    // $this->tableDeleteButton($highlight),

                    $helper->liveWireComponent(
                        component: EditHighlight::class,
                        id: "edit-highlight-{$highlight->id}",
                        params: [
                            $highlight->id,
                        ]
                    ),
                    $helper->liveWireComponent(
                        component: DeleteHighlight::class,
                        id: "delete-highlight-{$highlight->id}",
                        params: [
                            $highlight->id,
                        ]
                    ),
                ];

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setTheme('display', 'flex')
                    ->setTheme('flex', [
                        'gap-sm',
                    ]);
            }
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
