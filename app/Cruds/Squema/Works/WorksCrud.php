<?php

namespace App\Cruds\Squema\Works;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Works\Inputs\EndsAtFactory;
use App\Cruds\Squema\Works\Inputs\NameFactory;
use App\Cruds\Squema\Works\Inputs\PositionFactory;
use App\Cruds\Squema\Works\Inputs\StartsAtFactory;
use App\Cruds\Squema\Works\Inputs\SummaryFactory;
use App\Cruds\Squema\Works\Inputs\UrlFactory;
use App\Cruds\Squema\Works\Inputs\UserFactory;
use App\Cruds\Squema\Works\Inputs\UuidFactory;
use App\Livewire\Resume\Works\DeleteWork;
use App\Livewire\Resume\Works\EditWork;
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
        return $this->composeForm($this->inputsArray(), [
            'forms' => 'one-column',
        ]);
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs([SummaryFactory::NAME]);
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCell('Highlights', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var Work $work */
                $work = $model;

                return TableHelpers::highlightsButton(route('dashboard.works.highlights', [$work->id]));

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
            value: function ($value, Model $model) {

                /** @var Work $work */
                $work = $model;

                $helper = TableHelpers::make();

                $contents = [
                    // $helper->editButton(route('dashboard.works.edit', [$work->id])),
                    $helper->liveWireComponent(
                        component: EditWork::class, 
                        id: "edit-work-{$model->id}", 
                        params: ['workId' => $model->id,]
                    ),
                    $helper->liveWireComponent(
                        component: DeleteWork::class, 
                        id: "delete-work-{$model->id}", 
                        params: ['workId' => $model->id,]
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

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}
