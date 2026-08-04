<?php

namespace App\Livewire\Resume\Import;

use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteResumeImport extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $resumeImportId;

    public function mount(int $resumeImportId): void
    {
        $this->resumeImportId = $resumeImportId;
    }

    public function deleteImport(): void
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var ResumeImport $import */
        $import = $user->resumeImports()->findOrFail($this->resumeImportId);

        if (! ResumeImportCrud::canShowDeleteButton($import->status)) {
            session()->flash('custom_error', 'You cannot delete a resume import that is pending or processing.');

            return;
        }

        $import->delete();

        $this->dispatch('resume-updated');
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteImport',
            confirmMessage: 'Are you sure you want to delete this resume import record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.import.delete-resume-import')
            ->with('component', $this->getComponent());
    }
}
