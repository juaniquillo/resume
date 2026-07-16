<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Actions\Resume\CoverLetter\CreateCoverLetter as CreateCoverLetterAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Squema\CoverLetters\CoverLettersCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateCoverLetter extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $coverLetters = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->coverLetters);

        (new CreateCoverLetterAction(
            $validator->validated(),
            $user
        ))->handle();

        session()->flash('success', 'Cover letter created successfully.');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('')
            );

        $this->coverLetters = $output->toArray();
    }

    private function crud()
    {
        return CoverLettersCrud::build(
            values: $this->coverLetters,
            errors: $this->formErrors,
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function getModalKey(): string
    {
        return 'create-cover-letter';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Cover Letter',
                    id: $id,
                    variant: 'filled',
                    icon: self::CREATE_ICON,
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
        return view('livewire.resume.cover-letters.create-cover-letter')
            ->with('create', $this->getModal());
    }
}
