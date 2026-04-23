<?php

namespace App\Cruds\Squema\Locations;

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
use App\Cruds\Squema\Locations\Inputs\AddressFactory;
use App\Cruds\Squema\Locations\Inputs\BasicsFactory;
use App\Cruds\Squema\Locations\Inputs\CityFactory;
use App\Cruds\Squema\Locations\Inputs\CountryCodeFactory;
use App\Cruds\Squema\Locations\Inputs\PostalCodeFactory;
use App\Cruds\Squema\Locations\Inputs\RegionFactory;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class LocationsCrud implements CrudForm, CrudInterface, CrudTable
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
            BasicsFactory::make(),
            AddressFactory::make(),
            PostalCodeFactory::make(),
            CityFactory::make(),
            CountryCodeFactory::make(),
            RegionFactory::make(),
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

                /** @var Location $location */
                $location = $model;

                $contents = [
                    $this->tableEditButton($location),
                    $this->tableDeleteButton($location),
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

    public function tableEditButton(Location $location): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', route('dashboard.basics.locations.edit', [$location->id]))
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');
    }

    public function tableDeleteButton(Location $location): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', route('dashboard.basics.locations.destroy', [$location->id]))
            ->setAttribute('method', 'delete')
            ->setContent(
                FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('type', 'submit')
                    ->setContent('Delete')
                    ->setAttribute('size', 'xs')
                    ->setAttribute('variant', 'danger')
                    ->setAttribute('onclick', "return confirm('Are you sure you want to delete this location?')")
                    ->setTheme('cursor', 'pointer'),
            );
    }
}
