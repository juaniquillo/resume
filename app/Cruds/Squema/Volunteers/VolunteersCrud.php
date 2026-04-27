<?php

namespace App\Cruds\Squema\Volunteers;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Volunteers\Inputs\EndsAtFactory;
use App\Cruds\Squema\Volunteers\Inputs\OrganizationFactory;
use App\Cruds\Squema\Volunteers\Inputs\PositionFactory;
use App\Cruds\Squema\Volunteers\Inputs\StartsAtFactory;
use App\Cruds\Squema\Volunteers\Inputs\SummaryFactory;
use App\Cruds\Squema\Volunteers\Inputs\UrlFactory;
use App\Cruds\Squema\Volunteers\Inputs\UserFactory;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class VolunteersCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

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

    public function inputsArray(): array
    {
        return [
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
        return $this->formFullSpanInputs(['summary']);
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCell('Highlights', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var Volunteer $volunteer */
                $volunteer = $model;

                return TableHelpers::highlightsButton(route('dashboard.volunteers.highlights', [$volunteer->id]));
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

                /** @var Volunteer $volunteer */
                $volunteer = $model;

                $helper = TableHelpers::make();

                $contents = [
                    $helper->editButton(route('dashboard.volunteers.edit', [$volunteer->id])),
                    $helper->deleteButton(route('dashboard.volunteers.destroy', [$volunteer->id])),
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
}
