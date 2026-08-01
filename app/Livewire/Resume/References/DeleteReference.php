<?php

namespace App\Livewire\Resume\References;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteReference extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $referenceId;

    public function mount(int $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function deleteReference(): void
    {
        $user = $this->getUser();
        $id = $this->referenceId;
        $reference = $user->references()->findOrFail($id);
        $reference->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteReference',
            confirmMessage: 'Are you sure you want to delete this reference record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.references.delete-reference')
            ->with('component', $this->getComponent());
    }
}
