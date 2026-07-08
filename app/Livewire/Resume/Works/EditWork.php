<?php

namespace App\Livewire\Resume\Works;

use App\Actions\Resume\Work\UpdateWork;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Squema\Works\WorksCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class EditWork extends Component
{
    use IsLivewireForm;

    public array $works = [];

    #[Locked]
    public int $workId;

    public function mount(int $workId): void
    {
        $this->workId = $workId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $work = $this->getModel();

        $validator = $this->validateForm($this->crud($work)->make(), $this->works);

        (new UpdateWork(
            $validator->validated(),
            $work
        ))->handle();

        session()->flash('success', 'Work updated successfully.');

        $this->dispatch('resume-updated');

        $this->redirect(route('dashboard.works'));
    }

    #[On('resume-updated')]
    #[Computed]
    public function refreshVariables(): void
    {
        $work = $this->getModel();

        // format date output to be compatible with the input type="month"
        $workOutput = $this->crud($work)->make()->execute(
            new FormatDateAction(
                model: $work,
            )
        );

        $this->works = $workOutput->toArray();

    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Work
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Work $work */
        $work = $user->works()->findOrFail($this->workId);

        return $work;
    }

    private function crud(Work $work)
    {
        return WorksCrud::build(
            values: $this->works,
            errors: $this->formErrors,
            model: $work,
        );
    }

    public function render()
    {
        $work = $this->getModel();

        $crud = $this->crud($work);

        $form = $crud->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'updateForm()');

        return view('livewire.resume.works.edit-work')
            ->with('form', $form);
    }
}
