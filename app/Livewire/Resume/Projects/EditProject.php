<?php

namespace App\Livewire\Resume\Projects;

use App\Actions\Resume\Project\UpdateProject;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Projects\ProjectsCrud;
use App\Cruds\Schema\Projects\Renderers\ProjectsLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Project;
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

class EditProject extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $projects = [];

    #[Locked]
    public int $projectId;

    public function mount(int $projectId): void
    {
        $this->projectId = $projectId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $project = $this->getModel();

        $validator = $this->validateForm($this->crud($project)->make(), $this->projects);

        (new UpdateProject(
            $validator->validated(),
            $project
        ))->handle();

        Flux::toast(text: 'Project updated successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $project = $this->getModel();

        $projectOutput = $this->crud($project)->make()->execute(
            new FormatDateAction(
                model: $project,
            )
        );

        $this->projects = $projectOutput->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Project
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Project $project */
        $project = $user->projects()->findOrFail($this->projectId);

        return $project;
    }

    private function crud(Project $project)
    {
        return ProjectsCrud::build(
            values: $this->projects,
            errors: $this->formErrors,
            model: $project,
            formRenderer: ProjectsLivewireFormRenderer::make(),
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
        return "edit-project-{$this->projectId}";
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
        return view('livewire.resume.projects.edit-project')
            ->with('update', $this->getModal());
    }
}
