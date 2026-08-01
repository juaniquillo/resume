<?php

namespace App\Livewire\Resume\Education\Courses;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\Education;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteCourse extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $educationId;

    #[Locked]
    public int $courseId;

    public function mount(int $educationId, int $courseId): void
    {
        $this->educationId = $educationId;
        $this->courseId = $courseId;
    }

    public function deleteCourse(): void
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Education $education */
        $education = $user->education()->findOrFail($this->educationId);
        $course = $education->courses()->findOrFail($this->courseId);
        $course->delete();

        $this->dispatch('resume-updated');
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteCourse',
            confirmMessage: 'Are you sure you want to delete this course record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.education.courses.delete-course')
            ->with('component', $this->getComponent());
    }
}
