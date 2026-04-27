<?php

namespace App\Cruds\Squema\Skills;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Managers\ArrayToCommaSeparatedValueManager;
use App\Cruds\Squema\Skills\Inputs\KeywordsFactory;
use App\Cruds\Squema\Skills\Inputs\LevelFactory;
use App\Cruds\Squema\Skills\Inputs\NameFactory;
use App\Cruds\Squema\Skills\Inputs\UserFactory;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;

final class SkillsCrud implements CrudForm, CrudInterface, CrudTable
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
            'name' => NameFactory::make(),
            'level' => LevelFactory::make(),
            'keywords' => KeywordsFactory::make(),
        ];
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['keywords']);
    }

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {

                /** @var Skill $skill */
                $skill = $model;

                $helper = TableHelpers::make();

                $contents = [
                    $helper->editButton(route('dashboard.skills.edit', [$skill->id])),
                    $helper->deleteButton(route('dashboard.skills.destroy', [$skill->id])),
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

    /** @phpstan-ignore  return.unusedType */
    public function valueManager(): ?ValueManager
    {
        return new ArrayToCommaSeparatedValueManager;
    }
}
