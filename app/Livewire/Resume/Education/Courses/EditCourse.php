<?php

namespace App\Livewire\Resume\Education\Courses;

use App\Actions\Resume\Common\UpdateCourse;
use App\Cruds\Schema\Courses\CoursesCrud;
use App\Cruds\Schema\Courses\Renderers\CoursesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Course;
use App\Models\Education;
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

class EditCourse extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $courses = [];

    #[Locked]
    public int $educationId;

    #[Locked]
    public int $courseId;

    public function mount(int $educationId, int $courseId): void
    {
        $this->educationId = $educationId;
        $this->courseId = $courseId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $course = $this->getModel();

        $validator = $this->validateForm($this->crud($course)->make(), $this->courses);

        (new UpdateCourse(
            $validator->validated(),
            $course
        ))->handle();

        Flux::toast(text: 'Course updated successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $course = $this->getModel();
        $this->courses = $course->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Course
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Education $education */
        $education = $user->education()->findOrFail($this->educationId);
        /** @var Course $course */
        $course = $education->courses()->findOrFail($this->courseId);

        return $course;
    }

    private function crud(Course $course)
    {
        return CoursesCrud::build(
            values: $this->courses,
            errors: $this->formErrors,
            model: $course,
            formRenderer: CoursesLivewireFormRenderer::make(),
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
        return "edit-course-{$this->courseId}";
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
        return view('livewire.resume.education.courses.edit-course')
            ->with('update', $this->getModal());
    }
}
