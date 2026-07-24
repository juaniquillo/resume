<?php

namespace App\Cruds\Schema\CoverLetters;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\CoverLetters\Inputs\ContentFactory;
use App\Livewire\Resume\CoverLetters\PreviewCoverLetter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Override;

final class CoverLettersCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'cover_letter';

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
            'content' => ContentFactory::make(),
        ];
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['content']);
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    #[Override]
    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes(ContentFactory::NAME, self::getLivewireGroup());

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setThemes([
                'display' => 'flex',
                'flex' => 'gap-sm',
            ])
            ->setContents([
                'preview' => ComponentBuilder::make(PreviewCoverLetter::class)
                    ->setLivewire()
                    ->setLivewireKey('preview-cover-letter'),
                'save' => FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('type', 'submit')
                    ->setAttribute('variant', 'primary')
                    ->setAttribute('color', 'blue')
                    ->setAttributes([
                        'wire:loading.attr' => 'disabled',
                        'wire:target' => $livewireAttributes['wire:model'],
                    ])
                    ->setTheme('cursor', 'pointer')
                    ->setContent(__('Save')),
            ])
            ->setThemes([
                'display' => 'flex',
                'flex' => ['gap-sm'],
            ]);
    }
}




