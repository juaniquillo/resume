<?php

namespace App\Livewire\Resume\Skills;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteSkill extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $skillId;

    public function mount(int $skillId): void
    {
        $this->skillId = $skillId;
    }

    public function deleteSkill(): void
    {
        $user = $this->getUser();
        $id = $this->skillId;
        $skill = $user->skills()->findOrFail($id);
        $skill->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteSkill',
            confirmMessage: 'Are you sure you want to delete this skill record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.skills.delete-skill')
            ->with('component', $this->getComponent());
    }
}
