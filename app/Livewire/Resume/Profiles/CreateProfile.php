<?php

namespace App\Livewire\Resume\Profiles;

use App\Actions\Resume\Basics\StoreProfile as StoreProfileAcn;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Profiles\ProfilesCrud;
use App\Cruds\Schema\Profiles\Renderers\ProfilesLivewireFormRenderer;
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
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateProfile extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $profiles = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $basics = $user->resumeBasics();

        if (! $basics) {
            Flux::toast(heading: __('Error'), text: __('basics.errors.basics_not_found'), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->profiles);

        (new StoreProfileAcn(
            $validator->validated(),
            $basics
        ))->handle();

        Flux::toast(text: 'Profile created successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('')
            );

        $this->profiles = $output->toArray();
    }

    private function crud()
    {
        return ProfilesCrud::build(
            values: $this->profiles,
            errors: $this->formErrors,
            formRenderer: ProfilesLivewireFormRenderer::make(),
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
        return 'create-profile';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Profile',
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
        return view('livewire.resume.profiles.create-profile')
            ->with('create', $this->getModal());
    }
}
