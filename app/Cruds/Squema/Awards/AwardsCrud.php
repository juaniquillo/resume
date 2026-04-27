<?php

namespace App\Cruds\Squema\Awards;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Awards\Inputs\AwardedAtFactory;
use App\Cruds\Squema\Awards\Inputs\AwarderFactory;
use App\Cruds\Squema\Awards\Inputs\SummaryFactory;
use App\Cruds\Squema\Awards\Inputs\TitleFactory;
use App\Cruds\Squema\Awards\Inputs\UserFactory;
use App\Models\Award;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class AwardsCrud implements CrudForm, CrudInterface, CrudTable
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
            'title' => TitleFactory::make(),
            'awarder' => AwarderFactory::make(),
            'awarded_at' => AwardedAtFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
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

    /**
     * Runs once after all inputs
     * are processed
     */
    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {

                /** @var Award $award */
                $award = $model;

                $helper = TableHelpers::make();

                $contents = [
                    $helper->editButton(route('dashboard.awards.edit', [$award->id])),
                    $helper->deleteButton(route('dashboard.awards.destroy', [$award->id])),
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
