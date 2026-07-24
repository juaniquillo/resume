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
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Schema\Works\Renderers\WorksFormRenderer;
use App\Cruds\Schema\Works\Renderers\WorksTableRenderer;
use App\Cruds\Schema\Works\Inputs\EndsAtFactory;
use App\Cruds\Schema\Works\Inputs\NameFactory;
use App\Cruds\Schema\Works\Inputs\PositionFactory;
use App\Cruds\Schema\Works\Inputs\StartsAtFactory;
use App\Cruds\Schema\Works\Inputs\SummaryFactory;
use App\Cruds\Schema\Works\Inputs\UrlFactory;
use App\Cruds\Schema\Works\Inputs\UserFactory;
use App\Cruds\Schema\Works\Inputs\UuidFactory;
use App\Models\Work;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
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
    ) {}

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
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

    public function formNarrow(): BackendComponent|CompoundComponent
    {
        return WorksFormRenderer::make()->renderNarrow($this);
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return WorksFormRenderer::make()->renderFull($this, [SummaryFactory::NAME]);
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCell('Highlights', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var Work $work */
                $work = $model;

                $helper = TableHelpers::make();

                return TableHelpers::highlightsButton(route('dashboard.works.highlights', [$work->id]));

                // return $helper->liveWireComponent(
                //     component: Highlights::class,
                //     id: "work-highlights-{$work->id}",
                //     params: [$work->id]
                // );

            },
        ));
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: fn ($value, Model $model) => WorksTableRenderer::make()->renderSettings($model)
        );

        $action->setExtraCell('Settings', $recipe);
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}




