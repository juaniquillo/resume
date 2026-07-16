<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Cruds\Helpers\TableHelpers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteCoverLetter extends Component
{
    #[Locked]
    public int $coverLetterId;

    public function mount(int $coverLetterId): void
    {
        $this->coverLetterId = $coverLetterId;
    }

    public function deleteCoverLetter(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $coverLetter = $user->coverLetters()->findOrFail($this->coverLetterId);
        $coverLetter->delete();

        $this->dispatch('resume-updated');
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteCoverLetter',
            confirmMessage: 'Are you sure you want to delete this cover letter?',
        );
    }

    public function render()
    {
        return view('livewire.resume.cover-letters.delete-cover-letter')
            ->with('component', $this->getComponent());
    }
}
