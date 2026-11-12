<?php

namespace App\Cruds\Schema\ResumeReset;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\FluxBackendComponents\Factories\FluxComponentFactory;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

final class ResumeResetCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
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
        return [];
    }

    public function saveButton(): BackendComponent|CompoundComponent
    {
        return FluxComponentFactory::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'danger')
            ->setTheme('cursor', 'pointer')
            ->setAttribute('onclick', "return confirm('Are you sure you want to reset your resume? This action cannot be undone.')")
            ->setContent(__('Reset Everything'));
    }
}
