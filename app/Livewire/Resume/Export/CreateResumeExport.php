<?php

namespace App\Livewire\Resume\Export;

use App\Actions\Resume\Export\StoreResumeExport;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportLivewireFormRenderer;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use App\Support\ResumeLimit;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateResumeExport extends Component
{
    use IsLivewireForm;

    public array $resumeExport = [];

    public array $generalOptions = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {

        /** @var User $user */
        $user = Auth::user();

        if ($user->resumeExports()->count() >= ResumeLimit::EXPORTS) {
            Flux::toast(heading: __('Error'), text: ResumeLimit::errorMessage(__('resume exports'), ResumeLimit::EXPORTS), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->resumeExport);
        $optionsValidator = $this->validateForm(
            $this->optionsCrud()->make($this->optionsCrud()->optionsInputsArray()),
            $this->generalOptions
        );

        $export = (new StoreResumeExport)->handle($user, $validator->validated(), $optionsValidator->validated());

        $export->type->dispatchExportJob($export);

        Flux::toast(text: __('Resume export started successfully. It will be processed in the background.'), variant: 'success');

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

        $this->resumeExport = $output->toArray();
        $this->generalOptions = [];
    }

    private function crud()
    {
        return ResumeExportCrud::build(
            values: $this->resumeExport,
            errors: $this->formErrors,
            formRenderer: ResumeExportLivewireFormRenderer::make(),
        );
    }

    private function optionsCrud()
    {
        return GeneralOptionsCrud::build();
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
