<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Actions\Resume\CoverLetter\UpdateCoverLetter;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Squema\CoverLetters\CoverLettersCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\CoverLetter;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditCoverLetter extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $coverLetters = [];

    #[Locked]
    public int $coverLetterId;

    public function mount(int $coverLetterId): void
    {
        $this->coverLetterId = $coverLetterId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $coverLetter = $this->getModel();

        $validator = $this->validateForm($this->crud($coverLetter)->make(), $this->coverLetters);

        (new UpdateCoverLetter(
            $validator->validated(),
            $coverLetter
        ))->handle();

        session()->flash('success', 'Cover letter updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $coverLetter = $this->getModel();
        $this->coverLetters = $this->crud($coverLetter)->make()->execute(
            new NameValueAction(values: $coverLetter->toArray())
        )->toArray();
    }

    #[Computed]
    private function getModel(): CoverLetter
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var CoverLetter $coverLetter */
        $coverLetter = $user->coverLetters()->findOrFail($this->coverLetterId);

        return $coverLetter;
    }

    private function crud(CoverLetter $coverLetter)
    {
        return CoverLettersCrud::build(
            values: $this->coverLetters,
            errors: $this->formErrors,
            model: $coverLetter,
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-cover-letter-{$this->coverLetterId}";
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
                    themes: ['modal' => 'lg']
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.cover-letters.edit-cover-letter')
            ->with('update', $this->getModal());
    }
}
