<?php

namespace App\Livewire\Resume\References;

use App\Actions\Resume\Reference\UpdateReference;
use App\Cruds\Schema\References\ReferencesCrud;
use App\Cruds\Schema\References\Renderers\ReferencesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Reference;
use App\Models\User;
use Flux\Flux;
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

class EditReference extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $references = [];

    #[Locked]
    public int $referenceId;

    public function mount(int $referenceId): void
    {
        $this->referenceId = $referenceId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $reference = $this->getModel();

        $validator = $this->validateForm($this->crud($reference)->make(), $this->references);

        (new UpdateReference(
            $validator->validated(),
            $reference
        ))->handle();

        Flux::toast(text: 'Reference updated successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $reference = $this->getModel();

        $this->references = $reference->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Reference
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Reference $reference */
        $reference = $user->references()->findOrFail($this->referenceId);

        return $reference;
    }

    private function crud(Reference $reference)
    {
        return ReferencesCrud::build(
            values: $this->references,
            errors: $this->formErrors,
            model: $reference,
            formRenderer: ReferencesLivewireFormRenderer::make(),
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
        return "edit-reference-{$this->referenceId}";
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
        return view('livewire.resume.references.edit-reference')
            ->with('update', $this->getModal());
    }
}
