<?php

namespace App\Livewire\Resume\Basics;

use App\Actions\Resume\Basics\UpdateBasics as UpdateAction;
use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateBasics extends Component
{
    use IsLivewireForm, WithFileUploads;

    public array $basics = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $image = $this->basics[ImageFactory::NAME] ?? null;
        if (isset($image) && is_string($image)) {
            unset($this->basics[ImageFactory::NAME]);
        }

        $validator = $this->validateForm($this->crud()->make(), $this->basics);

        (new UpdateAction(
            $validator->validated(),
            $user,
            $this->basics[ImageFactory::NAME] ?? null
        ))->handle();

        session()->flash('success', 'Basics information updated successfully.');

        $this->redirect(route('dashboard.basics'));
    }

    #[On('resume-updated')]
    public function refreshVariables(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->basics = $user->basics?->toArray() ?? [];

    }

    private function crud()
    {
        /** @var User $user */
        $user = Auth::user();

        return BasicsCrud::build(
            values: $this->basics,
            errors: $this->formErrors,
            model: $user->resumeBasics(),
        );
    }

    public function render()
    {
        $crud = $this->crud();

        $form = $crud->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'updateForm()');

        return view('livewire.resume.basics.update-basics')
            ->with('form', $form);
    }
}
