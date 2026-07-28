<?php

namespace App\Livewire\Resume\Education\Courses;

use App\Actions\Resume\Education\CreateCourse as CreateCourseAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Courses\CoursesCrud;
use App\Cruds\Schema\Courses\Renderers\CoursesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Education;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CreateCourse extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $courses = [];

    #[Locked]
    public int $educationId;

    public function mount(int $educationId): void
    {
        $this->educationId = $educationId;
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Education $education */
        $education = $user->education()->findOrFail($this->educationId);

        $validator = $this->validateForm($this->crud()->make(), $this->courses);

        (new CreateCourseAction(
            $validator->validated(),
            $education
        ))->handle();

        session()->flash('success', 'Course created successfully.');

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

        $this->courses = $output->toArray();
    }

    private function crud()
    {
        return CoursesCrud::build(
            values: $this->courses,
            errors: $this->formErrors,
            formRenderer: CoursesLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->form()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function getModalKey(): string
    {
        return "create-course-{$this->educationId}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Course',
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
        return view('livewire.resume.education.courses.create-course')
            ->with('create', $this->getModal());
    }
}
