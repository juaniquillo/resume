<?php

namespace App\Livewire\Resume\Export;

use App\Cruds\Helpers\TableHelpers;
use App\Enums\ProcessStatus;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\ResumeExport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteResumeExport extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $resumeExportId;

    public function mount(int $resumeExportId): void
    {
        $this->resumeExportId = $resumeExportId;
    }

    public function deleteExport(): void
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var ResumeExport $export */
        $export = $user->resumeExports()->findOrFail($this->resumeExportId);

        if (! \in_array($export->status, [ProcessStatus::COMPLETED, ProcessStatus::FAILED])) {
            session()->put('custom_error', __('Only completed or failed exports can be deleted.'));

            return;
        }

        $export->delete();

        $this->dispatch('resume-updated');
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteExport',
            confirmMessage: 'Are you sure you want to delete this resume export record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.export.delete-resume-export')
            ->with('component', $this->getComponent());
    }
}
