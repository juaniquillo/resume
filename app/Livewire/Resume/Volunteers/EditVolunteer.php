<?php

namespace App\Livewire\Resume\Volunteers;

use App\Actions\Resume\Volunteer\UpdateVolunteer;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Volunteers\VolunteersCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Models\Volunteer;
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

class EditVolunteer extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $volunteers = [];

    #[Locked]
    public int $volunteerId;

    public function mount(int $volunteerId): void
    {
        $this->volunteerId = $volunteerId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $volunteer = $this->getModel();

        $validator = $this->validateForm($this->crud($volunteer)->make(), $this->volunteers);

        (new UpdateVolunteer(
            $validator->validated(),
            $volunteer
        ))->handle();

        session()->flash('success', 'Volunteer entry updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $volunteer = $this->getModel();

        $output = $this->crud($volunteer)->make()->execute(
            new FormatDateAction(
                model: $volunteer,
            )
        );

        $this->volunteers = $output->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Volunteer
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Volunteer $volunteer */
        $volunteer = $user->volunteers()->findOrFail($this->volunteerId);

        return $volunteer;
    }

    private function crud(Volunteer $volunteer)
    {
        return VolunteersCrud::build(
            values: $this->volunteers,
            errors: $this->formErrors,
            model: $volunteer,
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->form()
            ->setAttribute('wire:submit.prevent', 'updateForm');
    }

    public function getModalKey(): string
    {
        return "edit-volunteer-{$this->volunteerId}";
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
        return view('livewire.resume.volunteers.edit-volunteer')
            ->with('update', $this->getModal());
    }
}
