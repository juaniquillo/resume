<?php

namespace App\Livewire\Resume\Export;

use App\Actions\Resume\Export\UpdateResumeExport;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportUpdateLivewireFormRenderer;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\ResumeExport;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditResumeExport extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $resumeExport = [];

    #[Locked]
    public int $resumeExportId;

    public function mount(int $resumeExportId): void
    {
        $this->resumeExportId = $resumeExportId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $export = $this->getModel();

        $validator = $this->validateForm($this->crud($export)->make(), $this->resumeExport);

        (new UpdateResumeExport(
            $validator->validated(),
            $export
        ))->handle();

        session()->flash('success', __('Resume export updated successfully.'));

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $export = $this->getModel();

        $this->resumeExport = $export->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): ResumeExport
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var ResumeExport $export */
        $export = $user->resumeExports()->findOrFail($this->resumeExportId);

        return $export;
    }

    private function crud(ResumeExport $export)
    {
        return ResumeExportCrud::build(
            values: $this->resumeExport,
            errors: $this->formErrors,
            model: $export,
            formRenderer: ResumeExportUpdateLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->setSaveButtonLabel('Update Export')
            ->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-resume-export-{$this->resumeExportId}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Edit',
                    id: $id,
                    icon: self::EDIT_ICON,
                    size: 'xs'
                ),
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg'],
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.export.edit-resume-export')
            ->with('update', $this->getModal());
    }
}
