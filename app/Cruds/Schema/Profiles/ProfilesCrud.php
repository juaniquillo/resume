<?php

namespace App\Cruds\Schema\Profiles;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Schema\Profiles\Inputs\BasicsFactory;
use App\Cruds\Schema\Profiles\Inputs\NetworkFactory;
use App\Cruds\Schema\Profiles\Inputs\UrlFactory;
use App\Cruds\Schema\Profiles\Inputs\UsernameFactory;
use App\Cruds\Schema\Profiles\Inputs\UuidFactory;
use App\Cruds\Schema\Profiles\Renderers\ProfilesFormRenderer;
use App\Cruds\Schema\Profiles\Renderers\ProfilesTableRenderer;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ProfilesCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'profiles';

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
            'basics' => BasicsFactory::make(),
            'network' => NetworkFactory::make(),
            'username' => UsernameFactory::make(),
            'url' => UrlFactory::make(),
        ];
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return ProfilesFormRenderer::make()->renderFull($this, ['url']);
    }

    protected function tableOptions(TableRowsAction $action): void
    {
        ProfilesTableRenderer::make()->tableOptions($action);
    }
}
