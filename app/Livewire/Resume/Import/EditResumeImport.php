<?php

namespace App\Livewire\Resume\Import;

use App\Actions\Resume\Import\UpdateResumeImport;
use App\Cruds\Schema\ResumeImport\Renderers\ResumeImportUpdateLivewireFormRenderer;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\ResumeImport;
use App\Models\User;
use Flux\Flux;
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

class EditResumeImport extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $resumeImport = [];

    #[Locked]
    public int $resumeImportId;

    public function mount(int $resumeImportId): void
    {
        $this->resumeImportId = $resumeImportId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $import = $this->getModel();

        $crud = $this->crud($import);

        $validator = $this->validateForm(
            $crud->make($crud->inputsUpdateArray()),
            $this->resumeImport,
        );

        (new UpdateResumeImport(
            $validator->validated(),
            $import
        ))->handle();

        Flux::toast(text: __('Resume import updated successfully.'), variant: 'success');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $import = $this->getModel();

        $this->resumeImport = $import->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): ResumeImport
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var ResumeImport $import */
        $import = $user->resumeImports()->findOrFail($this->resumeImportId);

        return $import;
    }

    private function crud(ResumeImport $import)
    {
        return ResumeImportCrud::build(
            values: $this->resumeImport,
            errors: $this->formErrors,
            model: $import,
            formRenderer: ResumeImportUpdateLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->setSaveButtonLabel('Update Import Name')
            ->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-resume-import-{$this->resumeImportId}";
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
        return view('livewire.resume.import.edit-resume-import')
            ->with('update', $this->getModal());
    }
}
