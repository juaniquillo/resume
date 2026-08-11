<?php

namespace App\Livewire\Resume\Export;

use App\Actions\Resume\Export\CreateResumeExport as CreateResumeExportAction;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportLivewireFormRenderer;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use App\Enums\ResumeExportType;
use App\Jobs\ProcessCoverLetterPdfExport;
use App\Jobs\ProcessJsonExport;
use App\Jobs\ProcessPdfExport;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateResumeExport extends Component
{
    use IsLivewireForm;

    public array $resumeExport = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {

        /** @var User $user */
        $user = Auth::user();

        if ($user->resumeExports()->count() >= 5) {
            session()->flash('custom_error', __('You can only have up to 5 resume exports. Please delete an old one first.'));

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->resumeExport);

        $export = (new CreateResumeExportAction)->handle($user, $validator->validated());

        match ($export->type) {
            ResumeExportType::JSON => ProcessJsonExport::dispatch($export),
            ResumeExportType::PDF => ProcessPdfExport::dispatch($export),
            ResumeExportType::COVER_LETTER_PDF => ProcessCoverLetterPdfExport::dispatch($export),
        };

        session()->flash('success', __('Resume export started successfully. It will be processed in the background.'));

        $this->dispatch('resume-updated');

        $this->refreshVariables();

    }

    #[Computed]
    public function refreshVariables(): void
    {
        $this->resumeExport = [];
    }

    private function crud()
    {
        return ResumeExportCrud::build(
            values: $this->resumeExport,
            errors: $this->formErrors,
            formRenderer: ResumeExportLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->setSaveButtonLabel('Start New Export')
            ->form()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function render()
    {
        return view('livewire.resume.export.create-resume-export')
            ->with('create', $this->getForm());
    }
}
