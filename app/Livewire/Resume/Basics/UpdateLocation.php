<?php

namespace App\Livewire\Resume\Basics;

use App\Actions\Resume\Basics\UpdateLocation as UpdateAction;
use App\Cruds\Schema\Locations\LocationsCrud;
use App\Cruds\Schema\Locations\Renderers\LocationsLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
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
            Flux::toast(heading: __('Error'), text: __('basics.errors.basics_not_found'), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->location);

        (new UpdateAction(
            $validator->validated(),
            $basics
        ))->handle();

        Flux::toast(text: 'Location updated successfully.', variant: 'success');

        $this->redirect(route('dashboard.basics.location'));
    }

    #[On('resume-updated')]
    #[Computed]
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
            formRenderer: LocationsLivewireFormRenderer::make(),
        );
    }

    public function render()
    {
        $crud = $this->crud();

        $form = $crud->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');

        return view('livewire.resume.basics.update-location')
            ->with('form', $form);
    }
}
