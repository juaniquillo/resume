<?php

namespace App\Cruds\Schema\Locations;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Locations\Inputs\AddressFactory;
use App\Cruds\Schema\Locations\Inputs\BasicsFactory;
use App\Cruds\Schema\Locations\Inputs\CityFactory;
use App\Cruds\Schema\Locations\Inputs\CountryCodeFactory;
use App\Cruds\Schema\Locations\Inputs\PostalCodeFactory;
use App\Cruds\Schema\Locations\Inputs\RegionFactory;
use App\Cruds\Schema\Locations\Renderers\LocationsLivewireFormRenderer;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class LocationsCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'basics_location';

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
        protected FormRenderer $formRenderer = new LocationsLivewireFormRenderer,
    ) {}

    public static function build(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
        ?FormRenderer $formRenderer = null,
    ): static {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
            formRenderer: $formRenderer ?? LocationsLivewireFormRenderer::make(),
        );
    }

    public function inputsArray(): array
    {
        return [
            'city' => CityFactory::make(),
            'country_code' => CountryCodeFactory::make(),
            'basics' => BasicsFactory::make(),
            'address' => AddressFactory::make(),
            'postal_code' => PostalCodeFactory::make(),
            'region' => RegionFactory::make(),
        ];
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formRenderer->getForm($this);
    }

    public static function getLivewireGroup(): string
    {
        return 'location';
    }

    public function saveButton(): BackendComponent|CompoundComponent
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes('city', self::getLivewireGroup());

        $label = $this->saveButtonLabel;

        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setAttributes([
                'wire:loading.attr' => 'disabled',
                'wire:target' => $livewireAttributes['wire:model'] ?? 'updateForm',
            ])
            ->setTheme('cursor', 'pointer')
            ->setContent(__($label));
    }
}
