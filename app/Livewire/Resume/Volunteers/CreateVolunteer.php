<?php

namespace App\Livewire\Resume\Volunteers;

use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Volunteers\VolunteersCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use Flux\Flux;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Component;

class CreateVolunteer extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $volunteers = [];

    public function mount()
    {
        $this->refreshVariables();
    }

    private function crud()
    {
        return VolunteersCrud::build(
            errors: $this->formErrors,
        );
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->volunteers);

        $user->volunteers()->create($validator->validated());

        Flux::toast(text: 'Volunteer entry created successfully.', variant: 'success');

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

        $this->volunteers = $output->toArray();
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()->form()
            ->setAttribute('wire:submit.prevent', 'createForm');
    }

    public function getModalKey(): string
    {
        return 'create-volunteer';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Volunteer',
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
        return view('livewire.resume.volunteers.create-volunteer')
            ->with('create', $this->getModal());
    }
}
