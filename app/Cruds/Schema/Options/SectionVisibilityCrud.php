<?php

namespace App\Cruds\Schema\Options;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Managers\SettingsValueManager;
use App\Cruds\Schema\Options\Inputs\SectionSwitchFactory;
use App\Enums\ResumeSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Override;

final class SectionVisibilityCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'section_visibility';

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

    #[Override]
    public function inputsArray(): array
    {
        $inputs = [];
        foreach (ResumeSection::labels() as $key => $label) {
            $separatorKey = $key.'_separator';
            $inputs[$key] = SectionSwitchFactory::make($key, $label);
            $inputs[$separatorKey] = $this->separator($separatorKey);
        }

        return $inputs;
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'one-column',
        ];
    }

    #[Override]
    public function valueManager(): ValueManager
    {
        return new SettingsValueManager;
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }
}




