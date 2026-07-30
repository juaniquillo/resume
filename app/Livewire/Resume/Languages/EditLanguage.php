<?php

namespace App\Livewire\Resume\Languages;

use App\Actions\Resume\Language\UpdateLanguage;
use App\Cruds\Schema\Languages\LanguagesCrud;
use App\Cruds\Schema\Languages\Renderers\LanguagesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Language;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditLanguage extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $languages = [];

    #[Locked]
    public int $languageId;

    public function mount(int $languageId): void
    {
        $this->languageId = $languageId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $language = $this->getModel();

        $validator = $this->validateForm($this->crud($language)->make(), $this->languages);

        (new UpdateLanguage(
            $validator->validated(),
            $language
        ))->handle();

        session()->flash('success', 'Language updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $language = $this->getModel();

        $this->languages = $language->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Language
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Language $language */
        $language = $user->languages()->findOrFail($this->languageId);

        return $language;
    }

    private function crud(Language $language)
    {
        return LanguagesCrud::build(
            values: $this->languages,
            errors: $this->formErrors,
            model: $language,
            formRenderer: LanguagesLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-language-{$this->languageId}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Edit',
                    id: $id,
                    icon: self::EDIT_ICON,
                    size: 'xs'
                ),
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg'],
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.languages.edit-language')
            ->with('update', $this->getModal());
    }
}
