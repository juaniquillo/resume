<?php

namespace App\Cruds\Squema\Education;

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
use App\Cruds\Squema\Education\Inputs\AreaFactory;
use App\Cruds\Squema\Education\Inputs\EndsAtFactory;
use App\Cruds\Squema\Education\Inputs\InstitutionFactory;
use App\Cruds\Squema\Education\Inputs\ScoreFactory;
use App\Cruds\Squema\Education\Inputs\StartsAtFactory;
use App\Cruds\Squema\Education\Inputs\StudyTypeFactory;
use App\Cruds\Squema\Education\Inputs\UrlFactory;
use App\Cruds\Squema\Education\Inputs\UserFactory;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class EducationCrud implements CrudForm, CrudInterface, CrudTable
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
            'institution' => InstitutionFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'url' => UrlFactory::make(),
            'area' => AreaFactory::make(),
            'study_type' => StudyTypeFactory::make(),
            'score' => ScoreFactory::make(),
        ];
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {

                /** @var Education $education */
                $education = $model;

                $contents = [
                    FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                        ->setAttribute('href', route('dashboard.education.edit', $education->id))
                        ->setContent('Edit')
                        ->setAttribute('size', 'xs')
                        ->setTheme('cursor', 'pointer'),
                    ComponentBuilder::make(ComponentEnum::FORM)
                        ->setAttribute('action', route('dashboard.education.destroy', $education->id))
                        ->setAttribute('method', 'delete')
                        ->setContent(
                            FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                                ->setAttribute('type', 'submit')
                                ->setContent('Delete')
                                ->setAttribute('size', 'xs')
                                ->setAttribute('variant', 'danger')
                                ->setAttribute('onclick', "return confirm('Are you sure you want to delete this education entry?')")
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
