<?php

namespace App\Cruds\Squema\Basics;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Squema\Basics\Inputs\EmailFactory;
use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Cruds\Squema\Basics\Inputs\LabelFactory;
use App\Cruds\Squema\Basics\Inputs\NameFactory;
use App\Cruds\Squema\Basics\Inputs\PhoneFactory;
use App\Cruds\Squema\Basics\Inputs\SummaryFactory;
use App\Cruds\Squema\Basics\Inputs\UrlFactory;
use App\Cruds\Squema\Basics\Inputs\UserFactory;
use App\Cruds\Squema\Basics\Inputs\UuidFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Override;

final class BasicsCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'basics';

    public const MISSING_BASICS_ERROR = 'Basic information is required for exporting the resume.';

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
            'label' => LabelFactory::make(),
            'email' => EmailFactory::make(),
            'phone' => PhoneFactory::make(),
            'url' => UrlFactory::make(),
            'image' => ImageFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['summary']);
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    #[Override]
    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes(ImageFactory::NAME, self::getLivewireGroup());

        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setAttributes([
                "wire:loading.attr" => "disabled",
                "wire:target" => $livewireAttributes['wire:model'],
            ])
            ->setTheme('cursor', 'pointer')
            ->setContent(__($label));
    }
}
