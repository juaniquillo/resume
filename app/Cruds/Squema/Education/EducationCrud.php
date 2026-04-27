<?php

namespace App\Cruds\Squema\Education;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Education\Inputs\AreaFactory;
use App\Cruds\Squema\Education\Inputs\EndsAtFactory;
use App\Cruds\Squema\Education\Inputs\InstitutionFactory;
use App\Cruds\Squema\Education\Inputs\ScoreFactory;
use App\Cruds\Squema\Education\Inputs\StartsAtFactory;
use App\Cruds\Squema\Education\Inputs\StudyTypeFactory;
use App\Cruds\Squema\Education\Inputs\UrlFactory;
use App\Cruds\Squema\Education\Inputs\UserFactory;
use App\Cruds\Squema\Education\Inputs\UuidFactory;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
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
            'uuid' => UuidFactory::make(),
            'user' => UserFactory::make(),
            'institution' => InstitutionFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'area' => AreaFactory::make(),
            'study_type' => StudyTypeFactory::make(),
            'score' => ScoreFactory::make(),
            'url' => UrlFactory::make(),
        ];
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['url']);
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

                $helper = TableHelpers::make();

                $contents = [
                    $helper->editButton(route('dashboard.education.edit', [$education->id])),
                    $helper->deleteButton(route('dashboard.education.destroy', [$education->id])),
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
