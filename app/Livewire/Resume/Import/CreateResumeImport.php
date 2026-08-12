<?php

namespace App\Livewire\Resume\Import;

use App\Actions\Resume\Import\StoreResumeImport;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Schema\ResumeImport\Renderers\ResumeImportLivewireFormRenderer;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Jobs\ProcessResumeImport;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateResumeImport extends Component
{
    use IsLivewireForm,
        WithFileUploads;

    public array $resumeImport = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->resumeImports()->count() >= 5) {
            session()->flash('custom_error', __('You can only have up to 5 resume imports. Please delete an old one first.'));

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->resumeImport);

        $validatedData = $validator->validated();

        $import = (new StoreResumeImport)->handle($user, [
            JsonFileFactory::NAME => $validatedData[JsonFileFactory::NAME] ?? null,
        ]);

        dispatch(new ProcessResumeImport($import));

        session()->flash('success', __('Resume import started successfully. It will be processed in the background.'));

        $this->dispatch('resume-updated');

        $this->refreshVariables();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('')
            );

        $this->resumeImport = $output->toArray();
    }

    private function crud()
    {
        return ResumeImportCrud::build(
            values: $this->resumeImport,
            errors: $this->formErrors,
            formRenderer: ResumeImportLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->setSaveButtonLabel('Start New Import')
            ->form()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function render()
    {
        return view('livewire.resume.import.create-resume-import')
            ->with('form', $this->getForm());
    }
}
