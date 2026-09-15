<?php

namespace App\Livewire\Resume\Import;

use App\Actions\Resume\Import\StoreResumeImport;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Schema\ResumeImport\Inputs\NameFactory;
use App\Cruds\Schema\ResumeImport\Renderers\ResumeImportLivewireFormRenderer;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Jobs\ProcessResumeImport;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Support\ResumeLimit;
use Flux\Flux;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateResumeImport extends Component
{
    use IsLivewireForm,
        IsLivewireModal,
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

        if ($user->resumeImports()->count() >= ResumeLimit::IMPORTS) {
            Flux::toast(heading: __('Error'), text: ResumeLimit::errorMessage(__('resume imports'), ResumeLimit::IMPORTS), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->resumeImport);

        $validatedData = $validator->validated();

        $import = (new StoreResumeImport)->handle($user, [
            NameFactory::NAME => $validatedData[NameFactory::NAME] ?? null,
            JsonFileFactory::NAME => $validatedData[JsonFileFactory::NAME] ?? null,
        ]);

        dispatch(new ProcessResumeImport($import));

        Flux::toast(text: __('Resume import started successfully. It will be processed in the background.'), variant: 'success');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();
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

    public function getModalKey(): string
    {
        return 'create-import';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                // From trait
                'button' => $this->modalButton(
                    label: 'Import Resume',
                    id: $id,
                    variant: 'filled',
                    icon: self::CREATE_ICON,
                ),
                // From trait
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg']
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.import.create-resume-import')
            ->with('form', $this->getModal());
    }
}
