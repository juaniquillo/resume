<?php

namespace App\Livewire\Resume\Awards;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteAward extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $awardId;

    public function mount(int $awardId): void
    {
        $this->awardId = $awardId;
    }

    public function deleteAward(): void
    {
        $user = $this->getUser();
        $id = $this->awardId;
        $award = $user->awards()->findOrFail($id);
        $award->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteAward',
            confirmMessage: 'Are you sure you want to delete this award record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.awards.delete-award')
            ->with('component', $this->getComponent());
    }
}
