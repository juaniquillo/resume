<?php

namespace App\Cruds\Squema\Certificates;

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
use App\Cruds\Squema\Certificates\Inputs\DateFactory;
use App\Cruds\Squema\Certificates\Inputs\NameFactory;
use App\Cruds\Squema\Certificates\Inputs\UrlFactory;
use App\Cruds\Squema\Certificates\Inputs\UserFactory;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CertificatesCrud implements CrudForm, CrudInterface, CrudTable
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

                /** @var Certificate $certificate */
                $certificate = $model;

                $contents = [
                    $this->tableEditButton($certificate),
                    $this->tableDeleteButton($certificate),
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

    public function tableEditButton(Certificate $certificate): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', route('dashboard.certificates.edit', [$certificate->id]))
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');
    }

    public function tableDeleteButton(Certificate $certificate): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', route('dashboard.certificates.destroy', [$certificate->id]))
            ->setAttribute('method', 'delete')
            ->setContent(
                FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('type', 'submit')
                    ->setContent('Delete')
                    ->setAttribute('size', 'xs')
                    ->setAttribute('variant', 'danger')
                    ->setAttribute('onclick', "return confirm('Are you sure you want to delete this certificate?')")
                    ->setTheme('cursor', 'pointer'),
            );
    }
}
