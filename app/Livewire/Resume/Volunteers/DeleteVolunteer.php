<?php

namespace App\Livewire\Resume\Volunteers;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteVolunteer extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $volunteerId;

    public function mount(int $volunteerId): void
    {
        $this->volunteerId = $volunteerId;
    }

    public function deleteVolunteer(): void
    {
        $user = $this->getUser();
        $id = $this->volunteerId;
        $volunteer = $user->volunteers()->findOrFail($id);
        $volunteer->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteVolunteer',
            confirmMessage: 'Are you sure you want to delete this volunteer record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.volunteers.delete-volunteer')
            ->with('component', $this->getComponent());
    }
}
