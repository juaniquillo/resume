<?php

namespace App\Livewire\Options;

use App\Actions\Options\UpdateGeneralOptions as UpdateAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\GeneralOption;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateGeneralOptions extends Component
{
    use IsLivewireForm;

    public array $generalOptions = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->generalOptions);

        (new UpdateAction(
            $user,
            $validator->validated()
        ))->handle();

        $this->dispatch('resume-updated');
        $this->dispatch('resume-visibility-updated');

        session()->flash('success', 'Section visibility updated successfully.');

        $this->redirect(route('dashboard.resume.general'), true);
    }

    #[On('resume-visibility-updated')]
    public function refreshVariables(): void
    {
        $options = $this->generalOptions();
        $this->generalOptions = $options ? $options->toArray() : [];
    }

    public function generalOptions(): ?GeneralOption
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var ?GeneralOption $options */
        $options = $user->generalOptions()->first();

        return $options;
    }

    private function crud()
    {
        /** @var User $user */
        $user = Auth::user();

        return GeneralOptionsCrud::build(
            values: $this->generalOptions,
            errors: $this->formErrors,
            model: $this->generalOptions(),
        );
    }

    public function render()
    {
        $crud = $this->crud();

        $form = $crud->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');

        return view('livewire.options.update_general_options')
            ->with('form', $form);
    }
}
