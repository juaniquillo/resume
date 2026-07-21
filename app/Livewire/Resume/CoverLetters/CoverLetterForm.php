<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Actions\Resume\CoverLetter\SaveCoverLetter;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Squema\CoverLetters\CoverLettersCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\CoverLetter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CoverLetterForm extends Component
{
    use IsLivewireForm;

    public array $coverLetter = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function save(): void
    {
        $validator = $this->validateForm($this->crud()->make(), $this->coverLetter);

        (new SaveCoverLetter)->handle($validator->validated());

        session()->flash('success', 'Cover letter saved successfully.');

        $this->dispatch('resume-updated');
        $this->dispatch('cover-letter-updated');
        // $this->refreshVariables();

        session()->flash('success', 'The Cover Letter updated successfully.');

        // $this->redirect(route('dashboard.cover-letters'));
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $model = $this->getModel();
        $values = $model ? $model->toArray() : [];

        $output = $this->crud($values)
            ->make()
            ->execute(
                (new NameValueAction(values: $values))
            );

        $this->coverLetter = $output->toArray();
    }

    public function getModel(): ?CoverLetter
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var ?CoverLetter $model */
        $model = $user->coverLetters()->first();

        return $model;
    }

    private function crud(array $values = [])
    {
        return CoverLettersCrud::build(
            values: $values,
            errors: $this->formErrors,
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->coverLetter)
            ->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'save()');
    }

    public function render()
    {
        return view('livewire.resume.cover-letters.cover-letter-form')
            ->with('form', $this->getForm());
    }
}
