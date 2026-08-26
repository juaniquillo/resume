<?php

namespace App\Livewire\Resume\Education;

use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Education\EducationCrud;
use App\Cruds\Schema\Education\Renderers\EducationLivewireFormRenderer;
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
use Livewire\Component;

class CreateEducation extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $education = [];

    public function mount()
    {
        $this->refreshVariables();
    }

    private function crud()
    {
        return EducationCrud::build(
            errors: $this->formErrors,
            formRenderer: EducationLivewireFormRenderer::make(),
        );
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->education()->count() >= ResumeLimit::EDUCATION) {
            Flux::toast(heading: __('Error'), text: ResumeLimit::errorMessage(__('education entries'), ResumeLimit::EDUCATION), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->education);

        $user->education()->create($validator->validated());

        Flux::toast(text: 'Education entry created successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('')
            );

        $this->education = $output->toArray();
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->form()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function getModalKey(): string
    {
        return 'create-education';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Education',
                    id: $id,
                    variant: 'filled',
                    icon: self::CREATE_ICON,
                ),
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg']
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.education.create-education')
            ->with('create', $this->getModal());
    }
}
