<?php

namespace App\Livewire\Resume\Education\Courses;

use App\Cruds\Schema\Courses\CoursesCrud;
use App\Cruds\Schema\Courses\Renderers\CoursesLivewireTableRenderer;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\Education;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class CoursesTable extends Component
{
    use IsLivewireTable;

    #[Locked]
    public int $educationId;

    public function mount(int $educationId): void
    {
        $this->educationId = $educationId;
    }

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Education $education */
        $education = $user->education()->findOrFail($this->educationId);

        return $education->courses()->get();
    }

    private function crud()
    {
        return CoursesCrud::build(
            tableRenderer: CoursesLivewireTableRenderer::make(),
        );
    }

    private function table(): ?BackendComponent
    {
        $models = $this->getModels();
        if ($models->isEmpty()) {
            return null;
        }

        return $this->crud()->makeTable($models);
    }

    public function render()
    {
        return view('livewire.resume.education.courses.courses-table')
            ->with(['table' => $this->table()]);
    }
}
