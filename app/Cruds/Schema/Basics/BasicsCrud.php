<?php

namespace App\Cruds\Schema\Basics;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Schema\Basics\Inputs\EmailFactory;
use App\Cruds\Schema\Basics\Inputs\ImageFactory;
use App\Cruds\Schema\Basics\Inputs\LabelFactory;
use App\Cruds\Schema\Basics\Inputs\NameFactory;
use App\Cruds\Schema\Basics\Inputs\PhoneFactory;
use App\Cruds\Schema\Basics\Inputs\SummaryFactory;
use App\Cruds\Schema\Basics\Inputs\UrlFactory;
use App\Cruds\Schema\Basics\Inputs\UserFactory;
use App\Cruds\Schema\Basics\Inputs\UuidFactory;
use App\Cruds\Schema\Basics\Renderers\BasicsFormRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

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

    public function renderComposeForm(?array $inputs = null, ?array $themes = null): BackendComponent|CompoundComponent
    {
        return $this->composeForm($inputs, $themes);
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return BasicsFormRenderer::make()->renderFull($this, ['summary']);
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent
    {
        return BasicsFormRenderer::make()->saveButton($this, $label);
    }
}
