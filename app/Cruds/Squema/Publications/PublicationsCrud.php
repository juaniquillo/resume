<?php

namespace App\Cruds\Squema\Publications;

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
use App\Cruds\Squema\Publications\Inputs\DateFactory;
use App\Cruds\Squema\Publications\Inputs\IssuerFactory;
use App\Cruds\Squema\Publications\Inputs\NameFactory;
use App\Cruds\Squema\Publications\Inputs\UrlFactory;
use App\Cruds\Squema\Publications\Inputs\UserFactory;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class PublicationsCrud implements CrudForm, CrudInterface, CrudTable
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
            UserFactory::make(),
            NameFactory::make(),
            DateFactory::make(),
            IssuerFactory::make(),
            UrlFactory::make(),
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

                /** @var Publication $publication */
                $publication = $model;

                $contents = [
                    $this->tableEditButton($publication),
                    $this->tableDeleteButton($publication),
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

    public function tableEditButton(Publication $publication): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', route('dashboard.publications.edit', [$publication->id]))
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');
    }

    public function tableDeleteButton(Publication $publication): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', route('dashboard.publications.destroy', [$publication->id]))
            ->setAttribute('method', 'delete')
            ->setContent(
                FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('type', 'submit')
                    ->setContent('Delete')
                    ->setAttribute('size', 'xs')
                    ->setAttribute('variant', 'danger')
                    ->setAttribute('onclick', "return confirm('Are you sure you want to delete this publication?')")
                    ->setTheme('cursor', 'pointer'),
            );
    }
}
