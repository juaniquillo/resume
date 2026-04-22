<?php

namespace App\Cruds\Squema\Volunteers;

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
            'url' => UrlFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public function formAction(): string
    {
        return route('dashboard.volunteers.store');
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        $inputs = $this->inputsArray();
        $summary = $inputs['summary'] ?? null;

        if ($summary) {
            $inputs['summary'] = $this->spanFullContainer([
                $summary,
            ]);
        }

        return $this->form(
            inputs: $inputs,
        );
    }

    protected function extraCells(TableRowsAction $action): void
    {
        $action->setExtraCell('Highlights', new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var Volunteer $volunteer */
                $volunteer = $model;

                return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('href', route('dashboard.volunteers.highlights', [$volunteer->id]))
                    ->setContent('Highlights')
                    ->setAttribute('variant', 'primary')
                    ->setAttribute('color', 'amber')
                    ->setAttribute('size', 'xs')
                    ->setTheme('cursor', 'pointer');
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

                $contents = [
                    FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                        ->setAttribute('href', route('dashboard.volunteers.edit', $volunteer->id))
                        ->setContent('Edit')
                        ->setAttribute('size', 'xs')
                        ->setTheme('cursor', 'pointer'),
                    ComponentBuilder::make(ComponentEnum::FORM)
                        ->setAttribute('action', route('dashboard.volunteers.destroy', $volunteer->id))
                        ->setAttribute('method', 'delete')
                        ->setContent(
                            FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                                ->setAttribute('type', 'submit')
                                ->setContent('Delete')
                                ->setAttribute('size', 'xs')
                                ->setAttribute('variant', 'danger')
                                ->setAttribute('onclick', "return confirm('Are you sure you want to delete this volunteer?')")
                                ->setTheme('cursor', 'pointer'),
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
}
