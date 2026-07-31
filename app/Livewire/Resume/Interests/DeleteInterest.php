<?php

namespace App\Livewire\Resume\Interests;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteInterest extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $interestId;

    public function mount(int $interestId): void
    {
        $this->interestId = $interestId;
    }

    public function deleteInterest(): void
    {
        $user = $this->getUser();
        $id = $this->interestId;
        $interest = $user->interests()->findOrFail($id);
        $interest->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteInterest',
            confirmMessage: 'Are you sure you want to delete this interest record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.interests.delete-interest')
            ->with('component', $this->getComponent());
    }
}
