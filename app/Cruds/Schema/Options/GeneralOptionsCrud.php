<?php

namespace App\Cruds\Schema\Options;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Schema\Options\Inputs\HideAddressFactory;
use App\Cruds\Schema\Options\Inputs\HideEmailFactory;
use App\Cruds\Schema\Options\Inputs\HideImageFactory;
use App\Cruds\Schema\Options\Inputs\HidePhoneFactory;
use App\Cruds\Schema\Options\Inputs\IsDraftFactory;
use App\Cruds\Schema\Options\Inputs\SlugFactory;
use App\Cruds\Schema\Options\Inputs\ThemeSelectFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Override;

final class GeneralOptionsCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'general_options';

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

    /**
     * @return InputInterface[]
     */
    public static function slugInput(?int $id = null): array
    {
        return [
            SlugFactory::NAME => SlugFactory::make($id),
        ];
    }

    /**
     * @return InputInterface[]
     */
    #[Override]
    public function inputsArray(): array
    {
        return [
            ...self::slugInput(),
            ThemeSelectFactory::NAME => ThemeSelectFactory::make(),
            IsDraftFactory::NAME => IsDraftFactory::make(),
            $this->fieldsetWrap([
                HidePhoneFactory::NAME => HidePhoneFactory::make(),
                $this->separator('security_1'),
                HideAddressFactory::NAME => HideAddressFactory::make(),
                $this->separator('security_2'),
                HideEmailFactory::NAME => HideEmailFactory::make(),
                $this->separator('security_3'),
                HideImageFactory::NAME => HideImageFactory::make(),
            ], 'security', 'Security Options'),
        ];
    }

    #[Override]
    public function form(?array $inputs = null): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs([IsDraftFactory::NAME]);
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}
