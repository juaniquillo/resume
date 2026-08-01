<?php

namespace App\Livewire\Resume\Education;

use App\Actions\Resume\Education\UpdateEducation;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Education\EducationCrud;
use App\Cruds\Schema\Education\Renderers\EducationLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Education;
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

class EditEducation extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $education = [];

    #[Locked]
    public int $educationId;

    public function mount(int $educationId): void
    {
        $this->educationId = $educationId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $education = $this->getModel();

        $validator = $this->validateForm($this->crud($education)->make(), $this->education);

        (new UpdateEducation(
            $validator->validated(),
            $education
        ))->handle();

        session()->flash('success', 'Education entry updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $education = $this->getModel();

        $output = $this->crud($education)->make()->execute(
            new FormatDateAction(
                model: $education,
            )
        );

        $this->education = $output->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Education
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Education $education */
        $education = $user->education()->findOrFail($this->educationId);

        return $education;
    }

    private function crud(Education $education)
    {
        return EducationCrud::build(
            values: $this->education,
            errors: $this->formErrors,
            model: $education,
            formRenderer: EducationLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-education-{$this->educationId}";
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
        return view('livewire.resume.education.edit-education')
            ->with('update', $this->getModal());
    }
}
