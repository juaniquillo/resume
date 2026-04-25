<?php

namespace App\Cruds\Squema\Interests;

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
use App\Cruds\Squema\Interests\Inputs\KeywordsFactory;
use App\Cruds\Squema\Interests\Inputs\NameFactory;
use App\Cruds\Squema\Interests\Inputs\UserFactory;
use App\Cruds\Squema\Interests\Inputs\UuidFactory;
use App\Models\Interest;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class InterestsCrud implements CrudForm, CrudInterface, CrudTable
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
            'name' => NameFactory::make(),
            'keywords' => KeywordsFactory::make(),
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

                /** @var Interest $interest */
                $interest = $model;

                $contents = [
                    $this->tableEditButton($interest),
                    $this->tableDeleteButton($interest),
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

    public function tableEditButton(Interest $interest): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', route('dashboard.interests.edit', [$interest->id]))
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');
    }

    public function tableDeleteButton(Interest $interest): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', route('dashboard.interests.destroy', [$interest->id]))
            ->setAttribute('method', 'delete')
            ->setContent(
                FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('type', 'submit')
                    ->setContent('Delete')
                    ->setAttribute('size', 'xs')
                    ->setAttribute('variant', 'danger')
                    ->setAttribute('onclick', "return confirm('Are you sure you want to delete this interest?')")
                    ->setTheme('cursor', 'pointer'),
            );
    }
}
