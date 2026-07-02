<?php

namespace App\Livewire\Resume\Basics;

use App\Actions\Resume\Basics\UpdateLocation as UpdateAction;
use App\Cruds\Squema\Locations\LocationsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateLocation extends Component
{
    use IsLivewireForm;

    public array $location = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $basics = $user->resumeBasics();

        if (! $basics) {
            session()->flash('custom_error', __('basics.errors.basics_not_found'));

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->location);

        (new UpdateAction(
            $validator->validated(),
            $basics
        ))->handle();

        session()->flash('success', 'Location updated successfully.');

        $this->redirect(route('dashboard.basics.location'));
    }

    #[On('resume-updated')]
    public function refreshVariables(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $basics = $user->resumeBasics();

        $this->location = $basics?->location?->toArray() ?? [];
    }

    private function crud()
    {
        /** @var User $user */
        $user = Auth::user();

        return LocationsCrud::build(
            values: $this->location,
            errors: $this->formErrors,
            model: $user->resumeBasics()?->location,
        );
    }

    public function render()
    {
        $crud = $this->crud();

        $form = $crud->formWithInputsSpanFull()
            ->setAttribute('wire:submit.prevent', 'updateForm()');

        return view('livewire.resume.basics.update-location')
            ->with('form', $form);
    }
}
