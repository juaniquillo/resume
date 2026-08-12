<?php

namespace App\Livewire\Resume\Profiles;

use App\Actions\Resume\Basics\UpdateProfile;
use App\Cruds\Schema\Profiles\ProfilesCrud;
use App\Cruds\Schema\Profiles\Renderers\ProfilesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Profile;
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

class EditProfile extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $profiles = [];

    #[Locked]
    public int $profileId;

    public function mount(int $profileId): void
    {
        $this->profileId = $profileId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $profile = $this->getModel();

        $validator = $this->validateForm($this->crud($profile)->make(), $this->profiles);

        (new UpdateProfile(
            $validator->validated(),
            $profile
        ))->handle();

        Flux::toast(text: 'Profile updated successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $profile = $this->getModel();

        $this->profiles = $profile->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Profile
    {
        /** @var User $user */
        $user = Auth::user();

        $basics = $user->resumeBasics();

        /** @var Profile $profile */
        $profile = $basics?->profiles()->findOrFail($this->profileId) ?? abort(404);

        return $profile;
    }

    private function crud(Profile $profile)
    {
        return ProfilesCrud::build(
            values: $this->profiles,
            errors: $this->formErrors,
            model: $profile,
            formRenderer: ProfilesLivewireFormRenderer::make(),
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
        return "edit-profile-{$this->profileId}";
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
        return view('livewire.resume.profiles.edit-profile')
            ->with('update', $this->getModal());
    }
}
