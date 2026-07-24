<?php

namespace App\Livewire\Resume\Works;

use App\Actions\Resume\Work\UpdateWork;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Works\WorksCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Models\Work;
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

class EditWork extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

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

        (new FluxManager)->modal($this->getModalKey())->close();

        // $this->redirect(route('dashboard.works'));
    }

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

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->formNarrow()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-work-{$this->workId}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                // From trait
                'button' => $this->modalButton(
                    label: 'Edit',
                    id: $id,
                    icon: self::EDIT_ICON,
                    size: 'xs'
                ),
                // From trait
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg'],
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.works.edit-work')
            ->with('update', $this->getModal());
    }
}
