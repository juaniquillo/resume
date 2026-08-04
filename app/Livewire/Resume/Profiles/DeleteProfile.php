<?php

namespace App\Livewire\Resume\Profiles;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteProfile extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $profileId;

    public function mount(int $profileId): void
    {
        $this->profileId = $profileId;
    }

    public function deleteProfile(): void
    {
        $user = Auth::user();
        $basics = $user->resumeBasics();
        $profile = $basics?->profiles()->findOrFail($this->profileId);
        $profile?->delete();

        $this->dispatch('resume-updated');
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteProfile',
            confirmMessage: 'Are you sure you want to delete this profile record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.profiles.delete-profile')
            ->with('component', $this->getComponent());
    }
}
