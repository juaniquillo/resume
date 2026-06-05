<?php

namespace App\Cruds\Squema\Cache;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Presenters\ResumePresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class EmptyResumeCacheCrud implements CrudForm, CrudInterface
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

    public function handleCacheClear(): void
    {
        $user = Auth::user();

        if ($user) {
            (new ResumePresenter($user))->clearCache();
        }
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->form(
            inputs: $this->inputsArray(),
        );
    }

    public function saveButton(string $label = 'Clear Cache'): BackendComponent|CompoundComponent
    {
        $message = __('Are you sure you want to clear the cache?');

        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setAttribute('onclick', "return confirm('{$message}')")
            ->setTheme('cursor', 'pointer')
            ->setContent($label);
    }
}
