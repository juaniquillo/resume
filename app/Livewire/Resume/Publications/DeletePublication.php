<?php

namespace App\Livewire\Resume\Publications;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeletePublication extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $publicationId;

    public function mount(int $publicationId): void
    {
        $this->publicationId = $publicationId;
    }

    public function deletePublication(): void
    {
        $user = $this->getUser();
        $id = $this->publicationId;
        $publication = $user->publications()->findOrFail($id);
        $publication->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deletePublication',
            confirmMessage: 'Are you sure you want to delete this publication record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.publications.delete-publication')
            ->with('component', $this->getComponent());
    }
}
