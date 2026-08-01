<?php

namespace App\Cruds\Schema\Skills;

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
use App\Cruds\Schema\Skills\Inputs\KeywordsFactory;
use App\Cruds\Schema\Skills\Inputs\LevelFactory;
use App\Cruds\Schema\Skills\Inputs\NameFactory;
use App\Cruds\Schema\Skills\Inputs\UserFactory;
use App\Cruds\Schema\Skills\Inputs\UuidFactory;
use App\Cruds\Schema\Skills\Renderers\SkillsFormRenderer;
use App\Cruds\Schema\Skills\Renderers\SkillsTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class SkillsCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'skills';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected FormRenderer $formRenderer = new SkillsFormRenderer,
        protected TableRenderer $tableRenderer = new SkillsTableRenderer,
    ) {}

    public static function build(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
        ?FormRenderer $formRenderer = null,
        ?TableRenderer $tableRenderer = null,
    ): static {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
            formRenderer: $formRenderer ?? new SkillsFormRenderer,
            tableRenderer: $tableRenderer ?? new SkillsTableRenderer,
        );
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    public function inputsArray(): array
    {
        return [
            'user' => UserFactory::make(),
            'uuid' => UuidFactory::make(),
            'name' => NameFactory::make(),
            'level' => LevelFactory::make(),
            'keywords' => KeywordsFactory::make(),
        ];
    }

    public function form(?array $inputs = null): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public function formNarrow(): BackendComponent|CompoundComponent
    {
        return $this->form();
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
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
}
