<?php

namespace App\Livewire\Resume\Projects;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteProject extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $projectId;

    public function mount(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function deleteProject(): void
    {
        $user = $this->getUser();
        $id = $this->projectId;
        $project = $user->projects()->findOrFail($id);
        $project->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteProject',
            confirmMessage: 'Are you sure you want to delete this project record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.projects.delete-project')
            ->with('component', $this->getComponent());
    }
}
