<?php

namespace App\Livewire\Options;

use App\Actions\Options\UpdateSectionVisibility;
use App\Cruds\Squema\Options\SectionVisibilityCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SectionVisibility extends Component
{
    use IsLivewireForm;

    public array $sectionVisibility = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $this->sectionVisibility = (array) ($user->sectionVisibility->settings ?? []);
    }

    private function crud()
    {
        /** @var User $user */
        $user = Auth::user();

        return SectionVisibilityCrud::build(
            values: $this->sectionVisibility,
            errors: $this->formErrors,
            model: $user->sectionVisibility()->first()
        );
    }

    public function updateForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->sectionVisibility);

        (new UpdateSectionVisibility(
            $user,
            $this->sectionVisibility
        ))->handle();

        $this->dispatch('resume-order-updated');

        session()->flash('success', 'Section visibility updated successfully.');

        $this->redirect(route('dashboard.resume.visibility'), true);
    }

    public function render()
    {
        $crud = $this->crud();

        $form = $crud->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');

        return view('livewire.options.section-visibility', [
            'form' => $form,
        ]);
    }
}
