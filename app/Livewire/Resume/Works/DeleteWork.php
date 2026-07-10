<?php

namespace App\Livewire\Resume\Works;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteWork extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $workId;

    public function mount(int $workId): void
    {
        $this->workId = $workId;
    }

    public function deleteWork(): void
    {
        $user = $this->getUser();
        $id = $this->workId;
        $work = $user->works()->findOrFail($id);
        $work->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteWork',
            confirmMessage: 'Are you sure you want to delete this work?',
        );
    }

    public function render()
    {
        return view('livewire.resume.works.delete-work')
            ->with('component', $this->getComponent());
    }
}
