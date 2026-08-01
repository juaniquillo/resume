<?php

namespace App\Livewire\Resume\Education;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteEducation extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $educationId;

    public function mount(int $educationId): void
    {
        $this->educationId = $educationId;
    }

    public function deleteEducation(): void
    {
        $user = $this->getUser();
        $id = $this->educationId;
        $education = $user->education()->findOrFail($id);
        $education->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteEducation',
            confirmMessage: 'Are you sure you want to delete this education record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.education.delete-education')
            ->with('component', $this->getComponent());
    }
}
